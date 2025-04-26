<script setup>
import {ref} from 'vue'
import {router} from '@inertiajs/vue3'
import Header from "@/Components/Header.vue";
import EditSite from "@/Components/EditSite.vue";

const {site_list} = defineProps({
  site_list: Array,
})

const form = ref({
  name: '',
  url: ''
})

//登録、更新処理
const flashMessage = ref('')
const flashMessageType = ref('')
const showToast = ref(false)

const storeSite = () => {
  router.post('/save_site', form.value, {
    onSuccess: () => {
      form.value = {name: '', url: ''}
      router.reload({only: ['site_list']})
      closeEvent('success')
    },
    onError: (errors) => {
      console.error(errors)
      closeEvent('errors')
    }
  })
}

// トーストを表示して3秒後に非表示にする
const triggerToast = () => {
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

//モーダル処理
const showModal = ref(false)
const selectedSite = ref(null)

const openEditModal = (site) => {
  selectedSite.value = site
  showModal.value = true
}

const closeEvent = (result=false) => {
  showModal.value = false;
  if (result !==false ) {
    flashMessage.value = result==='success' ? '登録が完了しました' : 'エラーが発生しました'
    flashMessageType.value = result || 'errors'
    triggerToast()
  }
}
</script>

<template>
  <Header/>
  <div id="main_wrap">
    <transition name="toast">
      <div
          v-if="showToast"
          class="toast"
          :class="`${flashMessageType}_message`">
        {{ flashMessage }}
      </div>
    </transition>
    <div class="store_area">
      <form @submit.prevent="storeSite">
        <input v-model="form.name" placeholder="サイト名"/>
        <input v-model="form.url" placeholder="URL"/>
        <button type="submit">登録</button>
      </form>
    </div>
    <div class="site_list">
      <h2>登録サイト一覧</h2>
      <table>
        <tbody>
        <tr>
          <th>サイト名</th>
          <th>サイトURL</th>
          <th>登録日時</th>
          <th>操作</th>
        </tr>
        <tr v-for="site in site_list">
          <th>{{ site.name }}</th>
          <th>{{ site.url }}</th>
          <th>{{ site.created_at }}</th>
          <th>
            <button @click="openEditModal(site)">編集</button>
            <button>停止</button>
          </th>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <EditSite
      :site="selectedSite"
      :isOpen="showModal"
      :flashMessage="flashMessage"
      :flashMessageType="flashMessageType"
      @closeEvent="closeEvent"
  />
</template>

<style lang="scss" scoped>
#main_wrap {
  max-width: 1020px;
  margin: auto;
  padding: 40px 80px;
  font-size: 13px;

  .toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    min-width: 250px;
    padding: 15px 20px;
    border-radius: 8px;
    font-weight: bold;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    z-index: 9999;
  }

  .success_message {
    background-color: #d4edda;
    color: #155724;
  }

  .error_message {
    background-color: #f8d7da;
    color: #721c24;
  }

  .toast-enter-from {
    transform: translateX(100%);
    opacity: 0;
  }

  .toast-enter-active {
    transition: all 0.5s ease;
  }

  .toast-enter-to {
    transform: translateX(0);
    opacity: 1;
  }

  .toast-leave-from {
    transform: translateX(0);
    opacity: 1;
  }

  .toast-leave-active {
    transition: all 0.5s ease;
  }

  .toast-leave-to {
    transform: translateX(100%);
    opacity: 0;
  }

  input {
    border: 1px solid #E0E0E0;
    border-radius: 50px;
  }

  button {
    background: #0f0f0f;
    width: 100px;
    padding: 10px;
    color: #fff;
    border-radius: 50px;
  }

  .site_list {
    border: 1px solid #E0E0E0;
    border-radius: 8px;
    margin-top: 30px;

    h2 {
      padding: 15px;
      font-weight: bold;
    }

    table {
      width: 100%;

      tr {
        border-bottom: 1px solid #E0E0E0;
      }
    }
  }
}
</style>
