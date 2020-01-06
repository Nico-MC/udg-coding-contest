<template>
  <div class="export-button">
    <button v-on:click="exportDataAsCsv">Export table data as CSV</button>
  </div>
</template>

<script>

export default {
  data () {
    return {
      csvContent: "\ufeff" + this.categories.join(";") + "\n"
    }
  },
  props: {
    tableProducts: Array,
    categories: Array
  },
  methods: {
    exportDataAsCsv: function(e) {
      let tableProducts = this.tableProducts;
      /*
      Die CSV-Datei scheint kaputt zu sein,
      denn ich kann mir einige Zeilenumbrüche nicht erklären.
      Dementsprechend habe ich diese Logik geschrieben, die natürlich eigentlich
      serverseitig (also im Controller laufen sollte).
      Jedoch finde ich, dass es sinnvoller ist, die CSV richtig zu formatieren.
      BSP: Für den Reiter 'Beschreibung' gibt es folgenden Inhalt:
      ...
      "Single Jersey, Rundhalsausschnitt mit Rippstrickkragen,  \n
      Ärmelabschluss und Bund sind gekräuselt, Seitennähte,  \n
      Neutrales Größenetikett im Nacken. "
      ...
      Wozu die Zeilenumbrüche?
      Für eine Kategorie 3 Zeilen (bzw 5 Zellen) erscheint mir suspekt.
      */
      for (var i = 0; i < this.tableProducts.length; i++) {
        for (var j = 0; j < this.tableProducts[i].length; j++) {
          if(this.tableProducts[i][j].includes(" ")) {
            this.tableProducts[i][j] = '"' + this.tableProducts[i][j] + '"';
          }
        }
        this.csvContent += tableProducts[i].join(";") + "\n";
      }

      // Prepare CSV Download
      let exportedFilename = 'Artikel_edited.csv';
      let blob = new Blob([this.csvContent], { type: 'text/csv;charset=windows-1252;' });
      this.csvContent = this.categories.join(";") + "\n";
      var link = document.createElement("a");
      if (link.download !== undefined) { // feature detection
        // Browsers that support HTML5 download attribute
        var url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", exportedFilename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }
    }
  }
}
</script>

<style lang="css">
  .export-button {
    padding: 15px;
  }
</style>
