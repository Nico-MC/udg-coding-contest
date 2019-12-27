<template>
  <div class="">
    <h3 class="card-header text-center font-weight-bold text-uppercase py-4">{{ csvName }}</h3>
    <div class="options">
      <div class="option">
        <b-pagination
          :total-rows="totalRows"
          v-model="currentPage"
          :per-page="perPage"
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
        <span>Elements per Page</span>
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
            <td v-for="detail in sortedProduct">{{ detail }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script type="text/javascript">
  import { BPagination } from 'bootstrap-vue'
  import $ from "jquery";

  export default {
    components: {
      BPagination
    },
    props: {
      categories: Array,
      products: Array,
      csvName: String
    },
    data () {
      return {
        currentPage: 1,
        perPage: 5,
        currentSort: this.categories[0],
        currentSortDir: 'asc',
        search: ''
      }
    },
    computed: {
      totalRows () {
        return this.products.length
      },
      sortedProducts () {
        let sortedProducts = this.products.sort((a,b) => {
          let modifier = 1;
          let currentSort = this.categories.indexOf(this.currentSort);
          if(this.currentSortDir === 'desc') modifier = -1;
          if(a[currentSort] < b[currentSort]) return -1 * modifier;
          if(a[currentSort] > b[currentSort]) return 1 * modifier;
          return 0;
        });

        let slicedProducts = sortedProducts.slice(
          (this.currentPage - 1) * this.perPage,
          this.currentPage * this.perPage
        )
        return slicedProducts;
      }
    },
    methods: {
      sort: function (s) {
        //if s == current sort, reverse
        if(s === this.currentSort) {
          this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
        }
        this.currentSort = s;
      },
      filterProducts: function () {
        var value = this.search;
        $('.csv-table tbody tr').filter(function() {
          $(this).toggle($(this).text()
          .toLowerCase().indexOf(value) > -1)
        });
      },
      verifyCurrentPage ($event) {
        let currentPage = $event.target.valueAsNumber;
        if(currentPage > 0) {
          this.currentPage = currentPage;
        }
      },
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

  .onlyNumber {
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
