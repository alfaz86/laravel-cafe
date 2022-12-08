export const datatableLanguage = () => {
    return {
        paginate: {
            previous: "<<",
            next: ">>"
        },
    }
}

export const formatNumber = (number, rp = true) => {
    let str_number = number.toString().replace(/[^,\d]/g, "")
    if (!rp) return str_number
    let split = str_number.split(",")
    let sisa = split[0].length % 3
    let rupiah = split[0].substr(0, sisa)
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi)
    let separator = ""

    if (ribuan) {
        if (sisa) separator = "."
        rupiah += separator + ribuan.join(".")
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah
    return "Rp " + rupiah
}