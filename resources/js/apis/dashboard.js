import Api from "./Api";
export default {
    methods: {
        login(form) {
            return Api.post("/checklogin",form, {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },});
        },
    },
};
