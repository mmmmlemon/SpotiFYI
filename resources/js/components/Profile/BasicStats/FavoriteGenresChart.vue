<script>
import { Bar } from 'vue-chartjs'

export default {
  extends: Bar,
  data: () => ({
    chartData: null,
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  }),
  props: {
      favoriteGenres: { default: -1 },
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
          label: ['Твои любимые жанры'],
          backgroundColor: ['#1b77b9','#1bb98a','#48b91b','#b9941b','#b91b1b','#b91bb1','#4a1bb9','#223586','#228638','#864f22'],
          data: values
        }
      ]
    };

    this.renderChart(chartData, this.options)
  }
}
</script>