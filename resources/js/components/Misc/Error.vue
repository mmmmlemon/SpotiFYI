<template>
    <div class="container">
            <div class="bounce_in_anim" v-if="type=='normal'">
                <div class="row justify-content-center"><h1 class="warning_icon"><i class="fas fa-exclamation-triangle"></i></h1></div>
                <div class="row justify-content-center"><h5 class="error">Ошибка</h5></div>
                <div class="row justify-content-center"><h4>{{errorMessage}}</h4></div>
                <hr>
                <h6 class="row justify-content-center">Зайдите попозже или попробуйте еще раз</h6>
            </div>
            <div class="fade_in_anim" v-if="type=='small'">
                <div class="row justify-content-center"><h6 class="warning_icon"><i class="fas fa-exclamation-triangle"></i></h6></div>
                <div class="row justify-content-center"><h6 class="error">Ошибка</h6></div>
                <div class="row justify-content-center"><h6>{{errorMessage}}</h6></div>
            </div>
            <div class="fade_in_anim" v-if="type=='x-small'">
                <div class="row"><p style="font-size:12pt;" class="error">Ошибка: {{errorMessage}}</p></div>
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