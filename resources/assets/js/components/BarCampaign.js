import BarChart from 'vue-chartjs/src/BaseCharts/Bar';
import reactiveProp from 'vue-chartjs/src/mixins/reactiveProp';
export default BarChart.extend({
    mixins: [reactiveProp],
    props:['options'],

    mounted() {

        this.renderChart(this.chartData, this.options);
    }

    });
