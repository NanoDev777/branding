export default class Product {
    constructor(code = '', name = '', description = '', height = null, width = null, thickness = null, weight = null, category_id = null, items = []) {
        this.code = code;
        this.name = name;
        this.description = description;
        this.height = height;
        this.width = width;
        this.thickness = thickness;
        this.weight = weight;
        this.category_id = category_id;
        this.items = items;
    }
}