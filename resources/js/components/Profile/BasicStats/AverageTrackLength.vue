//AverageTrackLength
<template>
    <div class="row justify-content-center marginVertical" v-scroll="handleScroll" v-bind:class="{'zeroOpacity': visible === false}">
        <!-- средняя длина трека -->
        <div v-if="tracksMode === -1" class="col-11 col-md-6 paddingSides">
            <Loader />
        </div>
        <div v-else-if="tracksMode === false" class="col-11 col-md-6 paddingSides">
            <Error type="small" errorMessage="Не удалось загрузить треки" />
        </div>
        <div v-else-if="tracksMode != -1 && visible === true" class="col-12 text-center paddingSides">
            <div class="row justify-content-center">
                <div class="col-4 d-none d-md-block">
                    <i class="fas fa-ruler-horizontal mainColor slideLeftIcon " style="font-size: 16rem;"></i>
                </div>
                <div class="col-12 col-md-8 slideRight">
                    <h3 class="text-right paddingSides">
                        Средняя продолжительность трека - 
                        <b class="mainColorHighlight2 borderUnderline colorFadeIn">{{tracksMode}}</b>
                    </h3>
                </div>
            </div>
        </div>
        <div v-else>
             <Error type="small"/>
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
        tracksMode: {default: -1 },
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