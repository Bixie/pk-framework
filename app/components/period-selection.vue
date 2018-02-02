<template>

    <div>
        <div class="uk-grid uk-grid-small uk-grid-width-medium-1-4" data-uk-grid-margin>
            <div>
                <div class="uk-text-center">
                    <button type="button" @click="setPeriod('week')" class="uk-button uk-button-small uk-width-1-1"
                            :class="{'uk-active': current_period.type === 'week'}">{{ 'Week' | trans }}
                    </button>
                </div>
                <div v-if="overviewStats.week" class="uk-flex uk-flex-center">
                    {{{ overviewStats.week }}}
                </div>
            </div>
            <div>
                <div class="uk-text-center">
                    <button type="button" @click="setPeriod('month')" class="uk-button uk-button-small uk-width-1-1"
                            :class="{'uk-active': current_period.type === 'month'}">{{ 'Month' | trans }}
                    </button>
                </div>
                <div v-if="overviewStats.month" class="uk-flex uk-flex-center">
                    {{{ overviewStats.month }}}
                </div>
            </div>
            <div>
                <div class="uk-text-center">
                    <button type="button" @click="setPeriod('quarter')" class="uk-button uk-button-small uk-width-1-1"
                            :class="{'uk-active': current_period.type === 'quarter'}">{{ 'Quarter' | trans }}
                    </button>
                </div>
                <div v-if="overviewStats.quarter" class="uk-flex uk-flex-center">
                    {{{ overviewStats.quarter }}}
                </div>
            </div>
            <div>
                <div class="uk-text-center">
                    <button type="button" @click="setPeriod('year')" class="uk-button uk-button-small uk-width-1-1"
                            :class="{'uk-active': current_period.type === 'year'}">{{ 'Year' | trans }}
                    </button>
                </div>
                <div v-if="overviewStats.year" class="uk-flex uk-flex-center">
                    {{{ overviewStats.year }}}
                </div>
            </div>
        </div>

        <div v-show="current_period.type !== 'custom'"
             class="uk-flex uk-flex-middle uk-flex-space-between uk-margin-small-top">
            <button type="button" @click="setPeriod('custom')"
                    class="uk-button uk-button-small">{{ 'Custom period' | trans }}
            </button>
            <span>
                <a @click="timetravel(-1)" class="uk-icon-chevron-left uk-icon-hover uk-margin-small-right"></a>
                {{ current_period.label }}
                <a @click="timetravel(1)" class="uk-icon-chevron-right uk-icon-hover uk-margin-small-left"></a>
            </span>
            <strong>{{ $refs.date_from ? $refs.date_from.date : '' }}</strong>
            <strong>{{ $refs.date_to ? $refs.date_to.date : '' }}</strong>
        </div>

        <div v-show="current_period.type === 'custom'"
             class="uk-grid uk-grid-width-medium-1-2 uk-form-stacked uk-margin-small-top" data-uk-grid-margin>
            <div>
                <div class="uk-form-row">
                    <label class="uk-form-label">{{ 'Date from' | trans }}</label>
                    <div class="uk-form-controls">
                        <input-date-bix v-ref:date_from :datetime.sync="current_period.date_from"
                                        :show-time="false"></input-date-bix>
                    </div>
                </div>

            </div>
            <div>

                <div class="uk-form-row">
                    <label class="uk-form-label">{{ 'Date to' | trans }}</label>
                    <div class="uk-form-controls">
                        <input-date-bix v-ref:date_to :datetime.sync="current_period.date_to"
                                        :show-time="false"></input-date-bix>
                    </div>
                </div>

            </div>
        </div>

    </div>

</template>

<script>

import moment from 'moment';

export default {

    name: 'PeriodSelection',

    props: {
        'current_period': Object,
        'overviewStats': Object,
    },

    data: () => ({
        select_now: '',
        periods: {},
    }),

    created() {
        this.select_now = moment(this.current_period.select_now);
        this.setPeriod(this.current_period.type || 'week');
    },

    methods: {
        setPeriods() {
            const week = this.week(this.select_now);
            const month = this.month(this.select_now);
            const quarter = this.quarter(this.select_now);
            const year = this.year(this.select_now);
            this.periods = {
                week: {
                    type: 'week',
                    label: `${this.select_now.year()}-${this.select_now.isoWeek()}`,
                    select_now: this.select_now,
                    date_from: week.date_from,
                    date_to: week.date_to,
                    date_grouping: 'N',
                },
                month: {
                    type: 'month',
                    label: `${this.select_now.year()}-${this.select_now.month() + 1}`,
                    select_now: this.select_now,
                    date_from: month.date_from,
                    date_to: month.date_to,
                    date_grouping: 'j',
                },
                quarter: {
                    type: 'quarter',
                    label: `${this.select_now.year()}-${this.select_now.quarter()}`,
                    select_now: this.select_now,
                    date_from: quarter.date_from,
                    date_to: quarter.date_to,
                    date_grouping: 'W',
                },
                year: {
                    type: 'year',
                    label: `${this.select_now.year()}`,
                    select_now: this.select_now,
                    date_from: year.date_from,
                    date_to: year.date_to,
                    date_grouping: 'n',
                },
            };
        },

        setPeriod(type) {
            this.setPeriods();
            if (type === 'custom') {
                this.current_period.type = 'custom';
                this.current_period.label = this.$trans('Custom');
                return;
            }
            this.current_period = this.periods[type];
        },

        timetravel(dir) {
            this.$set('select_now', this.select_now[dir < 1 ? 'subtract' : 'add'](1, `${this.current_period.type}s`));
            this.setPeriod(this.current_period.type);
        },

        week(now) {
            const last_monday = moment(now).day(1);
            return this.getFullDays(last_monday, moment(last_monday).add(6, 'days'));
        },
        month(now) {
            const first_day = moment(now).date(1);
            const last_day = moment(first_day).add(1, 'months');
            return this.getFullDays(first_day, last_day.subtract(1, 'days'));
        },
        quarter(now) {
            const quarter = moment(now).quarter();
            const first_day = moment(now).month((quarter * 3) - 3);
            first_day.date(1);
            const last_day = moment(first_day).add(3, 'months');
            return this.getFullDays(first_day, last_day.subtract(1, 'days'));
        },

        year(now) {
            const first_day = moment(now).date(1);
            first_day.month(0);
            const last_day = moment(first_day).add(1, 'years');
            return this.getFullDays(first_day, last_day.subtract(1, 'days'));
        },

        getGraphUnits(period_type) {
            if (period_type === 'week') {
                return [[1, 'Mon',], [2, 'Tue',], [3, 'Wed',], [4, 'Thu',], [5, 'Fri',], [6, 'Sat',], [7, 'Sun',],];
            }
            if (period_type === 'month') {
                const units = [];
                for (let d = 1;d <= this.select_now.daysInMonth(); d+=1) {
                    units.push([d, d,]);
                }
                return units;
            }
            if (period_type === 'quarter') {
                return {
                    1: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13,],
                    2: [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26,],
                    3: [27, 28, 29, 30, 31, 32, 33, 34, 35, 37, 38, 39, 40,],
                    4: [41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53,],
                }[this.select_now.quarter()].map(wk => [wk,  `Week ${wk}`,]);
            }
            if (period_type === 'year') {
                return [[1, 'Jan',], [2, 'Feb',], [3, 'Mar',], [4, 'Apr',], [5, 'May',], [6, 'Jun',],
                    [7, 'Jul',], [8, 'Aug',], [9, 'Sep',], [10, 'Oct',], [11, 'Nov',], [12, 'Dec',],];
            }

        },

        getFullDays(start, end) {
            start.hours(0);
            start.minutes(0);
            start.seconds(0);
            end.hours(23);
            end.minutes(59);
            end.seconds(59);
            return {
                date_from: start.utc().format(),
                date_to: end.utc().format(),
            };

        },

    },
};

</script>
