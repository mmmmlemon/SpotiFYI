<template>
    <div>
        <div class="col-md-12 fade_in_slow_anim">
            <h5 class="text-center">
                <b>Последние прослушанные треки</b>&nbsp;
                <i class="fas fa-compact-disc primary_color"></i>
            </h5>
        </div>
        <div class="col-12">
            <div>
                <List />
            </div>
        </div>
    </div>
 
</template>
<script>
export default {
    mounted(){
        this.$store.dispatch('setCurrentTab', 'latest');
    },
}
</script>