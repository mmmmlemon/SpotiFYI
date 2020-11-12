<template>
    <div class="container">
        <h6>This is About page</h6>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        mounted() {
           console.log('%c%s', 'background-color: #34eb7d; font-weight: bold;', '\'About\' component mounted')
        }
    }
</script>