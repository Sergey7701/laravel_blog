<template>
	<div v-if="statistic.length">
		<h3>Результат готов: </h3>
			<div v-for="result in statistic" >
				<div v-for="item in result" >
					<div v-for="(value, name) in item" >
						<p class="col-12">
							{{ name }} {{ value }}
						</p>
					</div>	
					<hr>
				</div>	
			</div>		
	</div>	
</template>

<script>
    export default {
		data(){
			return {
				statistic: [
					
				]
			}	
		},
        mounted() {
			Echo.private(`App.User.${ userId }`).listen('StatisticReport', e => {
				this.statistic.push(e.statistic);
			});
			
		}
	}
</script>

<style>
	
</style>
