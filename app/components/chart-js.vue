<template>

    <div class="uk-position-relative">
        <canvas v-el:chart :width="config.width" :height="config.height"></canvas>
    </div>

</template>
<script>
/*global _ */
import Chart from 'chart.js';

export default {

    name: 'ChartJs',

    props: {
        'data': Object,
        'config': Object,
    },

    data: () => ({
        chart: {},
    }),

    events: {
        'chart.update': function () {
            this.$nextTick(() => {
                this.chart.data.labels = this.data.labels;
                this.chart.data.datasets = this.data.datasets;
                //hack to force update
                this.chart.chart.width -= 1;
                this.chart.chart.height -= 1;
                this.$nextTick(() => {
                    this.chart.resize();
                });
            });
        },
    },

    created() {
        this.config = _.merge({
            type: 'line',
            width: 400,
            height: 400,
            options: _.merge({
                legend: {
                    position: 'bottom',
                },
            }, this.config.options),
        }, this.config);
    },

    ready() {
        this.chart = new Chart(this.$els.chart, {
            type: this.config.type,
            data: this.data,
            options: this.config.options,
        });
    },

};


</script>