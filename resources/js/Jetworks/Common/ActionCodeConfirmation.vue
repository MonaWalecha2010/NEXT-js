<template>

    <div v-if="this.visible" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">
        <!--
          Background backdrop, show/hide based on modal state.

          Entering: "ease-out duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100"
            To: "opacity-0"
        -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center text-center sm:items-center sm:p-0">
                <!--
                  Modal panel, show/hide based on modal state.

                  Entering: "ease-out duration-300"
                    From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    To: "opacity-100 translate-y-0 sm:scale-100"
                  Leaving: "ease-in duration-200"
                    From: "opacity-100 translate-y-0 sm:scale-100"
                    To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                -->
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">

                    <div v-if="$slots.details" class="confirmation-instructions px-8 py-6 bg-gray-100">
                        <div class="text-center text-gray-400 text-sm font-semibold">
                            <slot name="details"/>
                        </div>
                    </div>

                    <div class="confirmation-instructions m-6">
                        <div class="text-center">
                            <h3 id="modal-title" class="text-xl font-semibold leading-6">
                                <span v-if="valid" class="text-gray-900">Enter your passcode</span>
                                <span v-else class="text-red-600">Incorrect passcode</span>
                            </h3>
                        </div>
                    </div>



                    <div class="confirmation-buttons">
                        <div class="text-center">
                            <div class="user-code flex justify-center">
                                <div class="user-code-keyboard">
                                    <div
                                        class="user-code-keyboard-code flex justify-center content-center gap-3 pb-8 text-4xl text-black">
                                        <template v-for="digit in code_length" ref="blahblah">
                                            <input
                                                :id="`code-${digit - 1}`"
                                                @focusin="clearPasswordInput"
                                                :value="code[(digit - 1)]"
                                                :ref="`code-${digit - 1}`"
                                                :data-code-position="(digit - 1)"
                                                :tabindex="digit"
                                                class="code_digit text-center text-4xl px-2 py-2.5 w-12 rounded-lg border-2 border-gray-100"
                                                maxlength="1"
                                                autocomplete="new-password"
                                                type="password">
                                        </template>
                                    </div>

                                    <div id="user-code-keyboard-keys"
                                         class="user-code-keyboard-keys px-10 flex flex-wrap justify-center items-center gap-5 text-4xl">
                                        <template v-for="digit in 10">
                                            <div
                                                class="flex items-center justify-center rounded-full h-20 w-20 bg-slate-100 font-semibold text-black cursor-pointer"
                                                :data-number="keypadNumber(digit)"
                                                @click="UserKeyboardClick">{{ keypadNumber(digit) }}
                                            </div>
                                        </template>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    <div
                        class="py-4 px-8 mt-5 sm:mt-8 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3 bg-gray-100">
                        <button class="jw-btn w-24 bg-white border-gray-200 text-gray-400" type="button"
                                @click="closeModal()">Cancel
                        </button>
                        <button class="jw-btn jw-btn__blue" type="button" @click="checkCode()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import { useCookies } from "vue3-cookies";
import {isProxy, toRaw} from 'vue';
import {Userhelper} from "@/Jetworks/Userhelper";

export default {
    name: 'ActionCodeConfirmation',
    data() {
        return {
            code: Array(6).fill(null),
            code_length: 6,
            code_position: 0,
            valid: true,
        }
    },
    props: {
        visible: {
            type: Boolean,
            default: false,
        }
    },
    setup( props ) {
        const { cookies } = useCookies();
        return { cookies };
    },
    computed: {
        Userhelper: function () {
            Userhelper.initialise(this.$page);
            return Userhelper;
        },
    },
    methods: {
        keypadNumber: function (number) {
            if (number == 10) {
                return 0;
            }
            return number;
        },

        clearPasswordInput(event) {
            let field = event.target;
            let position = parseInt(field.dataset.codePosition);
            this.code[position] = null;
            this.code_position = position;
        },

        UserKeyboardClick(event) {
            let value = parseInt(event.target.dataset.number);
            this.fillPassWordField(value);
            this.valid = true;
        },

        fillPassWordField(value) {
            if (this.code_position < this.code.length) {
                this.code[this.code_position] = value;
                this.code_position = parseInt(this.code_position) + 1;
                let inputRef = document.getElementById(`code-${this.code_position}`);
                if (inputRef) {
                    inputRef.focus();
                }
            }
        },
        clearPassWordFields() {
            this.code = Array(6).fill(null);
            this.code_position = 0;
        },

        closeModal: function () {
            this.clearPassWordFields();
            this.valid = true;
            this.$emit('closeCodeConfirmation', false);
        },

        preparedUserCode() {
            let codeArray = this.code;
            if (isProxy(this.code)) {
                codeArray = toRaw(this.code);
            }
            return codeArray.join('');
        },

        checkCode: function () {
            let preparedCode = this.preparedUserCode();
            axios.post(route('users.code', {'user': this.Userhelper.user.id}), {
                code: preparedCode,
            }).then((response) => {
                if ((response.data.valid === true) && (response.data.user_id == Userhelper.user.id)) {
                    //Set a cookie to store the User's auth code
                    let cookie_expires = this.Userhelper.code_expires * 60;
                    this.cookies.set("user_code", preparedCode, cookie_expires );
                    this.$emit('closeCodeConfirmation', preparedCode);
                }
            }).catch(error => {
                this.valid = false;
            })
                .finally(() => {
                    this.clearPassWordFields();
                });
        }
    },
    mounted() {
        let user_code = this.cookies.get("user_code");
        if( user_code ){
            this.code = user_code.split("");
        }else{
            let inputRef = document.getElementById('code-0');
            if (inputRef) {
                inputRef.focus();
            }
        }
    }
}
</script>
