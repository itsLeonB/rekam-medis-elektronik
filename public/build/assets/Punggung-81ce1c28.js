import{_ as v}from"./TextInput-181d0a07.js";import{_ as g}from"./InputError-892d609d.js";import{_ as x}from"./InputLabel-9cc85df5.js";import{r as n,bE as y,o as i,b as u,a as t,d as m,f as d}from"./app-bf8fb3e4.js";const b={class:"my-2 w-full"},h={class:"flex"},V={class:"w-full md:w-12/12"},k={class:"flex items-center"},j={key:0,class:"text-sm text-original-teal-300"},O={key:1,class:"text-sm text-thirdouter-red-300"},B={__name:"Punggung",props:{subject_reference:{type:Object,required:!1},practitioner_reference:{type:Object,required:!1},encounter_reference:{type:Object,required:!0}},setup(f,{expose:p}){const r=f;p({submit:_});const o=n({text:""}),a=n(!1),c=n(!1);function _(){const l=new Date().toISOString().replace("Z","+00:00").replace(/\.\d{3}/,""),e={resourceType:"Observation",status:"final",category:[{coding:[{system:"http://terminology.hl7.org/CodeSystem/observation-category",code:"exam",display:"Exam"}]}],code:{coding:[{system:"http://loinc.org",code:"10192-3",display:"Physical findings of Back Narrative"}]},subject:r.subject_reference,performer:[r.practitioner_reference],encounter:r.encounter_reference,effectiveDateTime:l,issued:l,valueString:o.value.text};y.post(route("integration.store",{res_type:e.resourceType}),e).then(s=>{a.value=!0,setTimeout(()=>{a.value=!1},3e3)}).catch(s=>{console.error("Error creating user:",s),c.value=!0,setTimeout(()=>{c.value=!1},3e3)})}return(l,e)=>(i(),u("div",null,[t("form",null,[t("div",b,[t("div",h,[t("div",V,[m(x,{for:"text",value:"Punggung"}),t("div",k,[m(v,{modelValue:o.value.text,"onUpdate:modelValue":e[0]||(e[0]=s=>o.value.text=s),id:"text",type:"text",class:"text-sm mt-1 mr-3 block w-full",placeholder:"Hasil Observasi",required:""},null,8,["modelValue"])]),m(g,{class:"mt-1"})])])]),a.value?(i(),u("p",j,"Sukses!")):d("",!0),c.value?(i(),u("p",O,"Gagal!")):d("",!0)])]))}};export{B as default};