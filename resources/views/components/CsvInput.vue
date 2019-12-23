<template>
  <div>
    <div class="csv-input form-group">
      <label for="csvHandler">Upload .csv</label>
      <input type="file" class="form-control-file" ref="fileInput">
    </div>

    <CsvTable :categories="categories" :products="products" v-if="categories.length != 0 && products.length != 0"/>
  </div>
</template>

<script>
import CsvTable from '@/views/components/csvTable.vue';
import axios from 'axios';

export default {
  components: {
    CsvTable
  },
  data () {
    return {
      'categories': [],
      'products': []
    }
  },
  methods: {
    handleCSV: function (file) {
      var formData = new FormData();
      formData.append('csv', file);

      axios.post('/handleCsv', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then((response) => {
        this.categories = response.data.categories;
        this.products = response.data.products;
      })
      .catch((error) => {
        console.log(error);
      });
    }
  },
  mounted () {
    var fileInput = this.$refs['fileInput'];
    fileInput.addEventListener('change', (e) => {
      e.preventDefault();
      this.handleCSV(e.target.files[0]);
    });
  }
}
</script>

<style lang="scss" scoped>
  .csv-input {
    margin: 15px;
  }
</style>
