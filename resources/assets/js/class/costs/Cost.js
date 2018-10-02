export default class Cost {
    constructor(chilean = '', dollar = '', purchase = '', sale ="" , transfer = '', amount = '', transport = '', iva = '') {
        this.chilean = chilean;
        this.dollar = dollar;
        this.purchase = purchase;
        this.sale = sale;
        this.transfer = transfer;
        this.amount = amount;
        this.transport = transport;
        this.iva = iva;
    }
}