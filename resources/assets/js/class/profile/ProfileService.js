export default class ProfileService {
    constructor(axios) {
        this._axios = axios
    }

    list() {
        return new Promise((resolve, reject) => {
            this._axios
            .then((response) => {
                resolve(response.data)
            })
           .catch((error) => {
               reject(error)
           })
        })
    }
}