<script setup>
import Header from "@/Components/Header.vue";

</script>

<template>
        <Header/>
        <main>
            <slot/>
        </main>
</template>

<style lang="scss">
#main_wrap {
    max-width: 1240px;
    margin: auto;
    padding: 16px 80px 24px;
    font-size: 13px;
    height: 100%;

    h2,
    p,
    span {
        color: #0f0f0f;
    }

    h2 {
        font-weight: bold;
        font-size: 14px;
        line-height: 2;
    }

    .content-wrap {
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        box-shadow: #eee 0 0 8px;
        padding: 8px 16px 16px;
    }
}
</style>
