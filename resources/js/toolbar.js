import CustomIndexToolbar from './components/toolbars/CustomIndexToolbar';
import MediaIndexToolbar from './components/toolbars/MediaIndexToolbar';

Nova.booting(Vue => {
  Vue.component('CustomIndexToolbar', CustomIndexToolbar);
  Vue.component('MediaIndexToolbar', MediaIndexToolbar);
});
