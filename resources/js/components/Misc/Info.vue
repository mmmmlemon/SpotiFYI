<template>
    <div class="container">
            <div class="bounceInAnim" v-if="type=='normal'">
                <div class="row justify-content-center"><h1 class="infoIcon"><i class="fas fa-exclamation-circle"></i></h1></div>
                <div class="row justify-content-center"><h5><b>Предупреждение</b></h5></div>
                <div class="row justify-content-center text-center"><h5>{{infoMessage}}</h5></div>
                <hr>
            </div>
            <div class="fadeInAnim" v-if="type=='small'">
                <div class="row justify-content-center"><h6 class="infoIcon"><i class="fas fa-exclamation-circle"></i></h6></div>
                <div class="row justify-content-center"><h6 class="warning">Предупреждение</h6></div>
                <div class="row justify-content-center"><h6>{{infoMessage}}</h6></div>
            </div>
            <div class="fadeInAnim" v-if="type=='x-small'">
                <div class="row"><p class="warning font12pt">Предупреждение: {{infoMessage}}</p></div>
            </div>
    </div>
</template>

<script>
export default {
    props: {
        infoMessage: { default: "Предупреждение", type: String },
        type: { default: 'normal', type: String },
    }
}
</script>