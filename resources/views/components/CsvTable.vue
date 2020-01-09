<template>
  <div>
    <h3 class="card-header text-center font-weight-bold text-uppercase py-4">{{ csvName }}</h3>
    <div class="options">
      <div class="option">
        <b-pagination
          :total-rows="totalRows"
          v-model="currentPage"
          :per-page="elementsPerPage"
          size="sm"
          class="csv-table-pagination"
        ></b-pagination>
      </div>
      <div class="option onlyNumber">
        <input type="number" :value="currentPage" v-on:keyup="verifyCurrentPage($event)">
        <span>Page</span>
      </div>
      <div class="option onlyNumber">
        <input type="number" v-model="perPage">
        <span>Elements per Page</span><span style="font-size: 8pt; padding-left: 3px">(1-500)</span>
      </div>
      <input v-on:keyup="filterProducts" class="search form-control mr-sm-2" type="search" v-model="search" placeholder="Search" aria-label="Search">
    </div>

    <div class="csv-table">
      <table class="table table-bordered table-responsive-md table-striped text-center">
        <thead>
          <tr>
            <th @click="sort(category)" v-if="index != 3" v-for="category, index in categories">{{ category }}</th>
            <th @click="sort(category)" v-else style="min-width: 400px">{{ category }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sortedProduct in sortedProducts">
            <td contenteditable="true" v-for="detail in sortedProduct">{{ detail }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="safe-data-button">
      <button v-on:click="safeData">Safe table data</button>
    </div>
    <CsvExport :csvName="csvName" :categories="categories" :tableProducts="tableProducts"/>
  </div>
</template>

<script type="text/javascript">
import CsvExport from '@/views/components/CsvExport.vue';
import { BPagination } from 'bootstrap-vue'
import $ from "jquery";

export default {
  components: {
    BPagination,
    CsvExport
  },
  props: {
    categories: Array,
    products: Array,
    csvName: String
  },
  data () {
    return {
      currentPage: 1,
      currentSort: this.categories[0],
      currentSortDir: 'asc',
      search: '',
      slicedProducts: [],
      lastPage: 1,
      /* clone products because I don't want to change data of diagram if changing data in table */
      tableProducts: JSON.parse(JSON.stringify(this.products)),
      elementsPerPage: 5
    }
  },
  computed: {
    totalRows () {
      return this.tableProducts.length
    },
    sortedProducts () {
      // Sort products
      let sortedProducts = this.tableProducts.sort((a,b) => {
        let modifier = 1;
        let currentSort = this.categories.indexOf(this.currentSort);
        if(this.currentSortDir === 'desc') modifier = -1;
        if(a[currentSort] < b[currentSort]) return -1 * modifier;
        if(a[currentSort] > b[currentSort]) return 1 * modifier;
        return 0;
      });

      // Slice products for pagination
      this.slicedProducts = sortedProducts.slice(
        (this.currentPage - 1) * this.perPage,
        this.currentPage * this.perPage
      )
      this.lastPage = this.currentPage;
      return this.slicedProducts;
    },
    perPage: {
      get () {
        return this.elementsPerPage;
      },
      set (val) {
        if (val > 0 && val <= 500) {
          this.elementsPerPage = val;
        } else if(val < 0) {
          this.elementsPerPage = 1;
        } else if(val > 500) {
          this.elementsPerPage = 500;
        }
      }
    }
  },
  methods: {
    sort: function (s) {
      // if s == current sort, reverse
      if(s === this.currentSort) {
        this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
      }
      this.currentSort = s;
    },
    filterProducts: function () {
      var value = this.search;
      $('.csv-table tbody tr').filter(function() {
        $(this).toggle($(this).text()
        .toLowerCase().indexOf(value.toLowerCase()) > -1)
      });
    },
    verifyCurrentPage ($event) {
      let currentPage = $event.target.valueAsNumber;
      if(currentPage > 0) {
        this.currentPage = currentPage;
      }
    },
    safeData () {
      // function to safe edited data
      let arr = [];
      let rows = document.querySelectorAll('.csv-table > .table tbody tr');

      rows.forEach((row, i) => {
        arr[i] = [];
        let cells = row.querySelectorAll('td');
        cells.forEach((cell, j) => {
          arr[i].push(cell.innerText);
        });
      });

      if(arr.length != 0) {
        var index = (this.lastPage - 1) * this.perPage;
        for(let i = index, j = 0; i < index + this.perPage; i++, j++) {
          this.tableProducts[i] = arr[j];
        }
      }
    }
  },
  updated () {
    this.filterProducts();
  }
}
</script>

<style lang="scss">
  .csv-table {
    width: 100%;
    overflow: auto;
  }

  .csv-table table {
    table-layout: auto;
  }

  .csv-table table th {
    cursor: pointer;
  }

  .options {
    margin: 15px;
  }

  .option {
    display: inline-block;
    margin-right: 15px;
  }

  .option input {
    width: 30px;
  }

  .search {
    max-width: 320px;
  }

  .safe-data-button {
    padding: 15px 15px 0 15px;
  }

  .onlyNumber {
    margin-bottom: 15px;
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance:textfield;
    }
  }
</style>
