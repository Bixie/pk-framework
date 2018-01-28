<template>

    <div>
        <style>.pac-container{z-index: 99999;}</style>

        <p class="uk-form-help-block uk-text-danger" v-show="fieldInvalid">{{ requiredMessage }}</p>

        <p class="uk-alert uk-alert-danger" v-if="invalidKey">{{{ invalidKey }}}</p>

        <div v-el:control class="uk-width-2-3 uk-margin-small-top uk-position-relative">
            <input type="text" v-el:search
                   v-model="location"
                   :id="fieldid" :name="fieldid"
                   class="uk-form-large uk-width-1-1"
                   v-validate:required="required">
            <a class="uk-close uk-close-alt uk-position-top-right" style="top:13px;right:13px"
               @click="clearLocation" :title="$trans('Clear')"></a>
        </div>
        <div v-el:map :style="minimumHeight" class="uk-cover uk-position-relative"></div>
    </div>

</template>

<script>

    import GmapsMixin from '../../app/mixins/gmaps';

    export default {

        name: 'InputLocation',

        mixins: [GmapsMixin,],

        props: {
            'location': {type: String, default: '',},
            'lat': {type: [Number, String], default: 0,},
            'lng': {type: [Number, String], default: 0,},
            'required': {type: Boolean, default: false,},
            'minHeight': {type: Number, default: 500,},
            'defaultLat': {type: Number, default: 52,},
            'defaultLng': {type: Number, default: 5,},
            'defaultZoom': {type: Number, default: 2,},
            'requiredMessage': {type: String, default: 'Please select a location',},
        },

        data: () => ({
            fieldid: _.uniqueId('location_'),
            map: {},
            inited: false,
        }),

        events: {
            'google.map.ready': 'initMap',
        },

        computed: {
            minimumHeight() {
                return `min-height: ${this.minHeight || 500}px`;
            },
            invalidKey() {
                if (!window.$pkframework.google_maps_key) {
                    return this.$trans('Please enter your Google Maps Javascript API key in the %link% settings!' ,
                        {'link': `<a href="${this.$url('/admin/system/package/extensions')}">Bixie Framework</a>`});
                }
                return false;
            },
            fieldInvalid() {
                return this.$parent.form && this.$parent.form[this.fieldid] ? this.$parent.form[this.fieldid].invalid : false;
            },
        },

        created() {
            this.lat = Number(this.lat);
            this.lng = Number(this.lng);
        },

        methods: {
            initMap() {
                const map = new google.maps.Map(this.$els.map, {
                    center: {
                        lat: this.defaultLat || 20,
                        lng: this.defaultLng || 0
                    },
                    zoom: this.defaultZoom || 2
                });
                this.map = map;

                map.controls[google.maps.ControlPosition.TOP_LEFT].push(this.$els.control);

                const autocomplete = new google.maps.places.Autocomplete(this.$els.search);
                autocomplete.bindTo('bounds', map);

                const infowindow = new google.maps.InfoWindow();
                const marker = new google.maps.Marker({
                    map: map,
                    anchorPoint: new google.maps.Point(0, 0)
                });

                if (this.location && this.lat && this.lng) {
                    this.centerMap();
                }

                autocomplete.addListener('place_changed',() => {
                    infowindow.close();
                    marker.setVisible(false);
                    const place = autocomplete.getPlace();
                    if (!place.geometry) {
                        // User entered the name of a Place that was not suggested and
                        // pressed the Enter key, or the Place Details request failed.
                        this.$notify(`No details available for input: '${place.name}'`);
                        return;
                    }

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(15);
                    }
                    marker.setIcon(/** @type {google.maps.Icon} */({
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(35, 35)
                    }));
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);

                    let address = '';
                    if (place.address_components) {
                        address = [
                            (place.address_components[0] && place.address_components[0].short_name || ''),
                            (place.address_components[1] && place.address_components[1].short_name || ''),
                            (place.address_components[2] && place.address_components[2].short_name || '')
                        ].join(' ');
                    }

                    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                    infowindow.open(map, marker);
                    this.setLocation(place);
                });
                this.inited = true;
            },

            clearLocation() {
                this.location = '';
                this.lat = 0;
                this.lng = 0;
                this.$dispatch('gmaps.location.picked', '');
            },

            centerMap() {
                this.map.setCenter({
                    lat: this.lat,
                    lng: this.lng
                });
                this.map.setZoom(15);
            },

            setLocation(place) {
                this.location = place.formatted_address;
                this.lat = place.geometry.location.lat();
                this.lng = place.geometry.location.lng();
                this.$dispatch('gmaps.location.picked', place.formatted_address);
            },
        },

    };

</script>
