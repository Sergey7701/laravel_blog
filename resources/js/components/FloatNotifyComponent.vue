<template>
    <div class="container vue-container">
        <div class="row justify-content-center">
            <div class="col-md-8 vue-flex">
                <div v-for="(reason, index) in reasons" class="vue-card">
                   <div class="card-header">
						<a style="cursor: pointer" onclick="$(this).parent().parent().addClass('d-none')">Х</a>
						<a style="color:white" v-bind:href="'/' + reason.prefix + '/' + reason.slug">
							{{ reason.header }}
						</a>
					</div>	
                    <div class="vue-card-body">
							{{ reason.message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
		data(){
			return {
				reasons: [
				]
			}	
		},
        mounted() {			
			let reason = {};
			Echo.private('editor-notify').listen('ArticleUpdated',  e => {
				switch (e.reason.service.prefix) {
					case 'posts': 
						reason.message = 'В статье изменилось: ';
						break;
					case 'news':	
						reason.message = 'В новости изменилось: ';
						break;
				}
				for (let key in e.reason.data){
					reason.message +=  key + ' ';
				}
				reason.prefix = e.reason.service.prefix;
				reason.slug = e.reason.service.slug;
				reason.header = e.reason.service.header;
				Vue.set(this.reasons, this.reasons.length, {...reason})
	        });
		}
	}
</script>

<style>
	.vue-container {
		width: 0;
		height: 0;
		position: fixed;
		bottom: 100px;
		right: 300px;
		overflow: visible;
		z-index: 1
	}
	.vue-card {
		opacity: 0.7;
		background: orange;
		width: 300px;
		border: 1px solid orange;
		border-radius: 10px;
		padding-left: 15px;
		padding-right: 15px;
	}
	.vue-flex{
		display: flex;
		flex-direction: column;
		justify-content: flex-end;
		height: 100px;
	}
</style>
