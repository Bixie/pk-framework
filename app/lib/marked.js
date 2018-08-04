
const MarkdownIt = window.markdownit;

const VueMarked = {
    install(Vue) {
        if (!MarkdownIt) {
            return;
        }
        const md = new MarkdownIt({
            html: true,
            linkify: true,
            typographer: true,
            breaks: true,
        });
        Vue.prototype.$marked = string => md.render(string);
    },
};

export default VueMarked;