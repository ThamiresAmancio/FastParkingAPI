
'use strict'

const getPreco = async (url) => {
    const response = await fetch(url)
    const json = await response.json()
    return await json[0]
}
const insertpreco = async (novoPreco) => {
    const url = 'http://fastparking.com.br/precos'
    const options = {
        method: 'POST',
        body: JSON.stringify(novoPreco)
    }
    await fetch(url, options)
}
const updatePreco = async (novoPreco) =>{
    const url = 'http://fastparking.com.br/precos/1'
    const options = {
        method: 'PUT',
        body: JSON.stringify(novoPreco)
    }
    await fetch(url, options)
}

const clearInputPrice = () => {
    document.querySelector('#primeiraHora').value = ''
    document.querySelector('#demaisHora').value = ''
}

const isValidForm = () => document.querySelector('.form').reportValidity()

const salvarPreco = async () => {
    if(isValidForm){
        const novoPreco =  {
                "primeirasHoras":document.querySelector('#primeiraHora').value.replace(",", "."),
                "demaisHoras": document.querySelector('#demaisHora').value.replace(",", ".")
            }
            if(await getPreco('http://fastparking.com.br/precos') == undefined){
                insertpreco(novoPreco)
                console.log("Inserido")
            }else{
                updatePreco(novoPreco)
                console.log("Atualizado")
            }
            clearInputPrice()
        }    


        window.location.href='index.html'
    }
const priceMask = (number) => {
    number = number.replace(/\D/g, "")
    number = number.replace(/(\d{1})(\d{5})$/, "$1.$2")
    number = number.replace(/(\d{1})(\d{1,2})$/, "$1,$2")
    return number
}

const applyMask = (event) => {
    event.target.value = priceMask(event.target.value)
}

document.querySelector('#adicionar').addEventListener('click', salvarPreco)
document.querySelector('#primeiraHora').addEventListener('keyup', applyMask)
document.querySelector('#demaisHora').addEventListener('keyup', applyMask)