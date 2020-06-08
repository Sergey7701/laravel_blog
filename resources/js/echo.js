/* Перезапускай сервер, если что!!! */
let channelName = 'lalala';
import FloatNotifyComponent from './components/FloatNotifyComponent.vue';
Vue.component('float-notify-component', FloatNotifyComponent);
if ($('#app').length) {
	const vm = new Vue({
		el: '#app'
	});
}
import StatisticResultComponent from './components/StatisticResultComponent.vue';
Vue.component('statistic-result-component', StatisticResultComponent);
if ($('#statistic-result').length) {
	const sr = new Vue({
		el: '#statistic-result'
	});
}