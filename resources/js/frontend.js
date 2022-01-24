export default {
    methods: {
        async callApi(method, url, data, headers) {
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: data,
                    headers: headers,
                });
            } catch (error) {
                return error.response;
            }
        },
    },
};
