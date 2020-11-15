<template>
    <div class="container">
            <div class="bounce_in_anim">
                <div class="row justify-content-center"><h1 class="warning_icon"><i class="fas fa-exclamation-triangle"></i></h1></div>
                <div class="row justify-content-center"><h5 class="error">Ошибка</h5></div>
                <div class="row justify-content-center"><h4>{{errorMessage}}</h4></div>
                <hr>
                <h6 class="row justify-content-center">Зайдите попозже или попробуйте еще раз</h6>
            </div>
    </div>
</template>

<script>
export default {
    props: {
        errorMessage: String
    }
}
</script>