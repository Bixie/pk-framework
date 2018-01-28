/*globals _*/

export default Vue => {

    Vue.filter('propsToString', props => {
        const values = [];
        _.forIn(props, propValue => {
            values.push(`${propValue.prop.label}: ${propValue.option.text}`);
        });
        return values.join(', ');
    });

};
