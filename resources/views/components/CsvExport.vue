<template>
  <div class="export-button">
    <button v-on:click="exportDataAsCsv">Export table data as CSV</button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    tableProducts: Array,
    categories: Array,
    csvName: String
  },
  methods: {
    exportDataAsCsv: function() {
      var formData = new FormData();
      formData.append('csvCategories', JSON.stringify(this.categories));
      formData.append('csvContent', JSON.stringify(this.tableProducts));

      axios.post('/exportCsv', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then((response) => {
        let contentType = response.headers['content-type'];
        let blob = new Blob(['\uFEFF' + response.data], { type: 'text/csv;charset=utf-8' });
        var url = URL.createObjectURL(blob);
        var link = document.createElement("a");
        link.href = url;
        link.download = "saved_" + this.csvName;

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      })
      .catch((error) => {
        console.log(error);
      });
    }
  }
}
</script>

<style lang="css">
  .export-button {
    padding: 15px;
  }
</style>
