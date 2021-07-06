//ArtistsCounr
<template>
    <div class="col-11 justify-content-center marginVertical" v-scroll="handleScroll" v-bind:class="{'zeroOpacity': visible === false}">
        <div class="row justify-content-center">
            <!-- лоадер -->
            <div v-if="uniqueArtists === -1">
                <Loader />
            </div>
            <!-- ошибка -->
            <div v-else-if="uniqueArtists === false">
                <Error type="small" errorMessage="Не удалось загрузить треки" />
            </div>
            <!-- контент -->
            <div v-else-if="uniqueArtists != -1 && uniqueArtists != false && visible === true" class="col-12 paddingSides goUpAnimSlow">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h3 class="text-center">В твоей библиотеке есть треки от</h3>
                        <h1 class="text-center borderUnderline mainColorHighlight2 colorFadeIn">{{uniqueArtists['countArtists']}}</h1>
                    </div>
                </div>

            </div>
            <!-- ошибка -->
            <div v-else>
                <Error type="small"/>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: () => {
        return {
            visible: false,
        }
    },

    props: {
        uniqueArtists: { default: -1 },
    },

    computed: {
        //видимость карточки
        setVisible: {
            get() {
                this.visible = false;
            },
            set(value){
                this.visible = value;
            }
        },
    },

    methods: {
        //при скролле страницы показать карточку когда она будет 
        //в поле видимости
        handleScroll: function (evt, el){
            if (el.getBoundingClientRect().top < 700) {
                this.setVisible = true;
            }
            return el.getBoundingClientRect().top < 700;   
        }
    }
}
</script>