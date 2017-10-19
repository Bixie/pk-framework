<template>

    <div class="uk-position-relative">
        <canvas v-el:chart :width="config.width" :height="config.height"></canvas>
    </div>

</template>
<script>

    var Chart = require('chart.js');

    module.exports = {

        name: 'chart-js',

        props: {
            'data': Object,
            'config': {Object},
        },

        data() {
            return {
                chart: {},
            };
        },

        created() {
            this.config = _.merge({
                type: 'line',
                width: 400,
                height: 400,
                options: _.merge({
                    legend: {
                        position: 'bottom',
                    }
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

        events: {
            'chart.update': function () {
                this.$nextTick(() => {
                    this.chart.data.labels = this.data.labels;
                    this.chart.data.datasets = this.data.datasets
                    //hack to force update
                    this.chart.chart.width -= 1;
                    this.chart.chart.height -= 1;
                    this.$nextTick(() => {
                        this.chart.resize();
                    });
                });
            }
        },
    };


</script>