<template>
    <div class="container">
        <div class="row justify-content-center">
                 <h1 v-if="this.$store.state.homePage.siteInfo != false" class="fade_in_anim"><b>{{this.$store.state.homePage.siteInfo.siteTitle}}</b></h1>
        </div>
        <div v-if="this.$store.state.homePage.siteInfo != false" class="row justify-content-center">
                <img src="/logo.png" width="6%" alt="" class="fade_in_anim bounce_in_anim">
        </div>
         <div v-if="this.$store.state.homePage.siteInfo != false" class="row justify-content-center">
                <h4 class="fade_in_anim">v0.0.1</h4>
        </div>
        <hr v-if="this.$store.state.homePage.siteInfo != false" class="fade_in_anim">
        <div v-if="this.$store.state.homePage.siteInfo != false" class="row justify-content-center fade_in_anim">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta magni veritatis dolorem velit debitis quae nesciunt doloribus dolore temporibus animi. Assumenda vero repudiandae rerum dignissimos quisquam, fugit eligendi consequuntur quidem.
            Unde ad facilis accusantium beatae at, fugiat voluptatem provident similique! Quidem reiciendis omnis quas vitae natus, animi fugiat, nostrum, dolores officiis dignissimos cum. Repellat voluptatum odio quod praesentium quos consectetur?
            Itaque maiores eius vel ut, reprehenderit iste cumque minus culpa ratione illo quibusdam omnis aliquid sint animi sit quis? Totam blanditiis minus sint repellat quidem numquam iusto sit a rem?
            Recusandae officia eveniet rem possimus voluptatum est autem tenetur architecto, ab esse, nulla numquam cumque beatae quasi officiis! Sint, sit. Consequuntur earum illo aut dicta. Porro ex odit nihil odio?</p>
        </div>
    </div>
</template>

<script>
    export default {
        created(){
            this.$store.dispatch('getSiteInfo')
        },
        mounted() {
           console.log('%c%s', 'background-color: #34eb7d; font-weight: bold;', '\'About\' component mounted')
        }
    }
</script>