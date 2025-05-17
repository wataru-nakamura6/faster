<script setup>
import { onBeforeUnmount, onMounted, reactive, watch } from 'vue';
import { useToast } from 'vue-toast-notification';
import axios from 'axios';
import { computed } from 'vue';

const props = defineProps({
    site: Object,
    isOpen: Boolean
});

const emits = defineEmits(['isClose']);

const UploadSite = reactive({
    id: null,
    url: '',
    upload_status: 0
});

watch(
    () => props.site,
    (newSite) => {
        if (newSite) {
            UploadSite.id = newSite.id;
            UploadSite.url = newSite.url;
            UploadSite.upload_status = newSite.upload_status;
        }
    },
    { immediate: true }
);

const canSubmit = computed(() => UploadSite.upload_status !== 1);
const reSubmit = computed(() => UploadSite.upload_status === 1);

const postMessageToIframe = (type) => {
    const iframe = document.getElementsByClassName('client_frame')[0];
    if (iframe && iframe.contentWindow) {
        try {
            iframe.contentWindow.postMessage(
                {
                    type: type,
                    data: {
                        message: '管理画面からの通知です',
                        siteId: UploadSite.id
                    }
                },
                UploadSite.url
            );
            console.log(`postMessage (${type}) を送信しました`);
        } catch (err) {
            console.error('postMessageの送信に失敗:', err);
            useToast().error('通信に失敗しました', { timeout: 5000 });
            saveStatusToDB(4);
            emits('isClose');
        }
    } else {
        useToast().error('iframe が見つかりません', { timeout: 5000 });
        saveStatusToDB(4);
        emits('isClose');
    }
};

const submitRoute = () => postMessageToIframe('button_clicked');
const submitFullReset = () => postMessageToIframe('full_reset');

const messageHandler = (event) => {
    const { type, status, failedMedia, message  } = event.data || {};
    if (type !== 'completed') return;
    console.log(failedMedia)

    switch (status) {
        case 'success':
            useToast().success('アップロードがすべて完了しました。', { timeout: 5000 });
            saveStatusToDB(1);
            break;

        case 'partial_fail':
            useToast().error(`一部のアップロードに失敗したため中断しました。`, { timeout: 5000 });
            saveStatusToDB(2);
            break;

        case 'failed_to_fetch_media':
            useToast().error('画像・動画の取得に失敗しました。', { timeout: 5000 });
            saveStatusToDB(3);
            break;

        default:
            useToast().error('不明なエラーが発生しました。', { timeout: 5000 });
            break;
    }
    const complete_message = (failedMedia !== null)? message+':'+failedMedia : message
    saveUploadLog(complete_message);

    emits('isClose');
    window.removeEventListener('message', messageHandler);
};

onMounted(() => {
    window.addEventListener('message', messageHandler);
});

onBeforeUnmount(() => {
    window.removeEventListener('message', messageHandler);
});

const saveStatusToDB = async (status) => {
    try {
        await axios.post('/site/site-status', {
            site_id: UploadSite.id,
            upload_status : status,
        });
    } catch (error) {
        console.error('ステータス保存に失敗しました', error);
    }
};
const saveUploadLog = async (message) => {
    try {
        await axios.post('/site/upload-log', {
            site_id: UploadSite.id,
            message,
        });
    } catch (error) {
        console.error('ログ保存に失敗しました', error);
    }
};
</script>

<template>
    <div v-if="isOpen" class="modal_overlay">
        <div class="modal_content">
            <h2>以下のURLを最適化しますか？</h2>
            <p>{{ UploadSite.url }}</p>
            <div class="notice">
                <p>※登録前に以下を確認お願いします</p>
                <ul>
                    <li>・高速化タグは head タグ内に埋め込み済みですか？</li>
                    <li>・サイトは完成した状態ですか？</li>
                </ul>
                <iframe
                    class="client_frame"
                    :src="UploadSite.url"
                    style="width:0; height:0; border:0; visibility:hidden;"
                ></iframe>
            </div>

            <div class="actions">
                <button v-if="canSubmit" @click="submitRoute" class="submit">最適化する</button>
<!--                TODO:更新処理-->
                <button v-if="reSubmit" @click="submitFullReset" class="reset">全て再登録</button>
                <button @click="$emit('isClose')">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.modal_overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;

    .modal_content {
        background: white;
        padding: 30px;
        border-radius: 8px;
        width: 500px;

        h2{
            padding: 15px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }

        p{
            text-align: center;
            padding: 5px 0;
        }

        input{
            border: 1px solid #E0E0E0;
            border-radius: 50px;
            margin-bottom: 5px;
            width: 100%;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;

            button{
                background: #0f0f0f;
                width: 100px;
                padding: 10px;
                color: #fff;
                border-radius: 50px;

                &.submit{
                    background: #930000;
                }
            }
        }
    }
}
</style>
