export default class User {
    constructor(name = '', email = '', password = '', password_confirmation ="" , profile_id = '') {
        this.name = name;
        this.email = email;
        this.password = password;
        this.profile_id = profile_id;
    }
}