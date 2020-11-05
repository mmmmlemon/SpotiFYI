<template>
    <div class="container">
        <h4>Test</h4>
        <ul>
            <li><a href="/test_spotify">test spotify web api</a></li>
            <li><a href="/test_auth">test auth code flow</a></li>
            <li><a href="/test_cookies">test cookies</a></li>
            <li><a href="/test_library">test library</a></li>
        </ul>

        <h4>Settings</h4>
        <table class="table">
            <tr>
                <th>site_title</th>
                <th>spotify_client_id</th>
                <th>spotify_client_secret</th>
                <th>spotify_redirect_uri</th>
            </tr>
            <tr>
                <td>{{settings.site_title}}</td>
                <td>{{settings.spotify_client_id}}</td>
                <td>{{settings.spotify_client_secret}}</td>
                <td>{{settings.spotify_redirect_uri}}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('\'tests\' component mounted')
        },

        data(){
            return {
                settings: ""
            } 
        },

        created(){
           let uri = '/api/test_api';
           this.axios.get(uri).then((response) => {
               this.settings = response.data;
           });
        }
    }
</script>