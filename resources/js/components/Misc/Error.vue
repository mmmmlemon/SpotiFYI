<template>
    <div class="container">
            <div class="bounceInAnim" v-if="type=='normal'">
                <div class="row justify-content-center"><h1 class="warningIcon"><i class="fas fa-exclamation-triangle"></i></h1></div>
                <div class="row justify-content-center"><h5 class="error"><b>Ошибка</b></h5></div>
                <div class="row justify-content-center text-center"><h5>{{errorMessage}}</h5></div>
                <hr>
                <h6 class="row justify-content-center">Зайдите попозже или попробуйте еще раз</h6>
            </div>
            <div class="fadeInAnim" v-if="type=='small'">
                <div class="row justify-content-center"><h6 class="warningIcon"><i class="fas fa-exclamation-triangle"></i></h6></div>
                <div class="row justify-content-center"><h6 class="error">Ошибка</h6></div>
                <div class="row justify-content-center text-center"><h6>{{errorMessage}}</h6></div>
            </div>
            <div class="fadeInAnim" v-if="type=='x-small'">
                <div class="row"><p class="error font12pt">Ошибка: {{errorMessage}}</p></div>
            </div>
    </div>
</template>

<script>
export default {
    props: {
        errorMessage: { default: "Неизвестная ошибка", type: String },
        type: { default: 'normal', type: String },
    }
}
</script>