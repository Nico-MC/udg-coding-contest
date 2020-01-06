<template>
  <div id="chart">
    <apexchart height="1000px" type="bar" :options="chartData.chartOptions" :series="chartData.series"></apexchart>
  </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'

export default {
  name: 'CsvChart',
  components: {
    apexchart: VueApexCharts
  },
  computed: {
    chartData: function () {
      let str = this.categories.indexOf('Produktart');
      let productTypes = [];
      this.products.forEach(product => {
        let currentProductType = product[str];
        if(currentProductType in productTypes) {
          productTypes[currentProductType] += 1;
        } else {
          productTypes[currentProductType] = 1;
        }
      });

      let labels = Object.keys(productTypes);
      let data = Object.values(productTypes).sort(function(a, b) { return b - a });

      let sum = 0;
      for (let i of data) {
        sum += i;
      }

      return {
        series: [{
          data: data
        }],
        chartOptions: {
          chart: {
            type: 'bar'
          },
          plotOptions: {
            bar: {
              barHeight: '100%',
              distributed: true,
              horizontal: true,
              dataLabels: {
                position: 'bottom'
              },
            }
          },
          colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4', '#90ee7e',
            '#f48024', '#69d2e7', '#31b7de', '#543B7C', '#d4725f', '#12e8ba', '#A5874C', '#2b207c', '#f7a3b3', '#80ae7d',
              '#e47123', '#63d2c6'
          ],
          dataLabels: {
            total: {
              show: true,
              label: 'TOTAL'
            },
            enabled: true,
            textAnchor: 'start',
            style: {
              colors: ['#fff']
            },
            formatter: function (val, opt) {
              let percentage = (100 / sum) * val; // calculate percentage
              percentage = Math.round(percentage * 100) / 100 // round
              return opt.w.globals.labels[opt.dataPointIndex] + ":  " + percentage + " %"
            },
            offsetX: 0,
            dropShadow: {
              enabled: true
            }
          },
          stroke: {
            width: 1,
            colors: ['#fff']
          },
          xaxis: {
            categories: labels
          },
          yaxis: {
            labels: {
              show: false
            }
          },
          title: {
              text: 'All types of products',
              align: 'center',
              floating: true
          },
          subtitle: {
              text: 'Colors have no importance.',
              align: 'center',
          },
          tooltip: {
            theme: 'dark',
            x: {
              show: false
            },
            y: {
              title: {
                formatter: function () {
                  return ''
                }
              }
            }
          }
        }
      }
    }
  },
  props: {
    categories: Array,
    products: Array
  }
}
</script>

<style lang="scss">
  #chart {
    margin-top: 50px;
    padding-top: 10px;
    background-color: #f2f2f2;
  }
</style>
