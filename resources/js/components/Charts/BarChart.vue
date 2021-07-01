<script>
import { HorizontalBar } from 'vue-chartjs'

export default {
  extends: HorizontalBar,
  data: () => ({
    chartData: null,
   options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { display: false },
        scales: {
          yAxes: [{
            gridLines: {
              display: false,
            },
            ticks: {
              fontFamily: 'Montserrat',
              fontColor: '#e8e6e6',
              fontSize: 16,
            },
          }],
          xAxes: [{
             gridLines: {
              width: 2,
            },
            ticks: {
              fontFamily: 'Montserrat',
              fontColor: '#1ea74c',
              fontSize: 14,
            },
          }]
        }
      }
  }),
  props: {
      favoriteGenres: { default: -1 },
      backgroundColor: { default: '#1b77b9' },
      label: { default: "Заголовок" },
  },

  async mounted () {
    
    //получаем заголовки и значения
    var keys = Object.keys(this.favoriteGenres);
    var values = Object.values(this.favoriteGenres);
    //добавляем 0 в конец, чтобы столбцы отображались как надо
    values.push(0);

    var chartData = {
      labels: keys,
      datasets: [
        {
          label: this.label,
          backgroundColor: this.backgroundColor,
          data: values
        }
      ]
    };

    this.renderChart(chartData, this.options)
  }
}
</script>