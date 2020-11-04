<template>
    <div class="container">
        <h3>Test</h3>
        <ul>
            <li><a href="/test_spotify">test spotify web api</a></li>
            <li><a href="/test_auth">test auth code flow</a></li>
            <li><a href="/test_cookies">test cookies</a></li>
        </ul>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('\'tests\' component mounted')
        }
    }
</script>