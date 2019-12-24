<template>
  <div class="">
    <h3 class="card-header text-center font-weight-bold text-uppercase py-4">{{ csvName }}</h3>
    <div class="options">
      <div class="pagination-option">
        <b-pagination
          :total-rows="totalRows"
          v-model="currentPage"
          :per-page="perPage"
          size="sm"
          class="csv-table-pagination"
        ></b-pagination>
      </div>
      <div class="numberation-option">
        <input ref="numb" type="text" name="productsPerPage" v-model="perPage">
        <span>Elements per Page</span>
      </div>
    </div>

    <div class="csv-table">
      <table class="table table-bordered table-responsive-md table-striped text-center">
        <thead>
          <tr>
            <th v-if="index != 3" v-for="category, index in categories">{{ category }}</th>
            <th v-else style="min-width: 400px">{{ category }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="list in lists">
            <td v-for="detail in list">{{ detail }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script type="text/javascript">
  import { BPagination } from 'bootstrap-vue'

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
        perPage: 5
      }
    },
    computed: {
      lists () {
        return this.products.slice(
          (this.currentPage - 1) * this.perPage,
          this.currentPage * this.perPage
        )
      },
      totalRows () {
        return this.products.length
      }
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

  .options {
    margin: 15px;
  }

  .option, .pagination-option, .numberation-option {
    display: inline-block;
  }

  .pagination-option {
    margin-right: 15px;
  }

  .numberation-option input {
    width: 30px;
  }
</style>









<!-- <div class="card">
  <h3 class="card-header text-center font-weight-bold text-uppercase py-4">Csv name</h3>
  <div class="card-body">
    <div class="table-editable">
      <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-success"><i
            class="fas fa-plus fa-2x" aria-hidden="true"></i></a></span>
      <b-pagination
      :total-rows="totalRows"
      v-model="currentPage"
      :per-page="perPage"
      class="csv-table-pagination"
      ></b-pagination>
      <table class="csv-table table table-bordered table-responsive-md table-striped text-center">
        <thead>
          <tr>
            <th v-for="category in categories" class="text-center">{{ category }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="list in lists">
            <td v-for="detail in list" class="pt-3-half" contenteditable="true">{{ detail }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div> -->
