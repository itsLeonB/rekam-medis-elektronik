import{r as a,aD as n,o as s,b as i}from"./app-f0d7f9c0.js";const d=["value"],c={__name:"TextInput",props:{modelValue:{type:String,required:!0}},emits:["update:modelValue"],setup(u,{expose:t}){const e=a(null);return n(()=>{e.value.hasAttribute("autofocus")&&e.value.focus()}),t({focus:()=>e.value.focus()}),(l,o)=>(s(),i("input",{class:"block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm",value:u.modelValue,onInput:o[0]||(o[0]=r=>l.$emit("update:modelValue",r.target.value)),ref_key:"input",ref:e},null,40,d))}};export{c as _};