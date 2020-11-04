<template>
    <div class="container">
           <h1>Site title</h1>
            <h4>A Laravel/Vue.js/Spotify Web API application</h4>
            <hr>
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1024px-Laravel.svg.png" width="100px" alt="">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/95/Vue.js_Logo_2.svg/1024px-Vue.js_Logo_2.svg.png" width="100px" alt="">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Spotify_logo_with_text.svg" width="100px" alt="">
            <hr>
             <router-link to="/tests"><i class="fa fa-dashboard"></i>Tests</router-link>
            <p>blah blah blah some content here</p>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('\'home_page\' component mounted')
        }
    }
</script>