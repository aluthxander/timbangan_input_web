// BasicFunctions.js
export default class BasicFunctions {
    // Properti statis
    static description = "class ini berisi fungsi-fungsi dasar pada web.";

    // Konstruktor untuk menginisialisasi properti
    constructor() {
        // Properti instance
        this.name = "class fungsi dasar";
        this.version = "1.0.0";
    }

    // methode statis untuk mengambil parameter
    static getParameterByName(name) {
        name = name.replace(/[\[\]]/g, "\\$&");
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        return params.get(name);
    }
}
