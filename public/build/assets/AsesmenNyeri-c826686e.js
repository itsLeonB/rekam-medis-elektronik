import{r as c,o as l,b as u,a as e,d,h as p,bp as v,w as h,f as m,g as b,e as y,bE as g}from"./app-f0d7f9c0.js";import{_ as x}from"./MainButtonSmall-93327a9a.js";import{_ as w}from"./InputError-5b64019f.js";import{_ as S}from"./InputLabel-1f97d873.js";const B=["onSubmit"],T={class:"my-2 w-full"},j=e("h3",{class:"font-semibold text-secondhand-orange-300 mt-2"},"Asesmen Nyeri",-1),k={class:"flex"},N={class:"w-full md:w-12/12"},V={class:"flex"},A=e("option",{value:"false"},"Tidak",-1),O=e("option",{value:"true"},"Ya",-1),q=[A,O],C={class:"flex justify-end"},D={class:"mt-2 mr-3"},E={key:0,class:"text-sm text-original-teal-300"},$={key:1,class:"text-sm text-thirdouter-red-300"},P={__name:"AsesmenNyeri",props:{subject_reference:{type:Object,required:!1},practitioner_reference:{type:Object,required:!1},encounter_reference:{type:Object,required:!0}},setup(_){const o=_,r=c({valueBoolean:!0}),a=c(!1),i=c(!1),f=()=>{const n=new Date().toISOString().replace("Z","+00:00").replace(/\.\d{3}/,""),t={resourceType:"Observation",status:"final",category:[{coding:[{system:"http://terminology.hl7.org/CodeSystem/observation-category",code:"survey",display:"Survey"}]}],code:{coding:[{system:"http://snomed.info/sct",code:"22253000",display:"Pain"}]},subject:o.subject_reference,performer:[o.practitioner_reference],encounter:o.encounter_reference,effectiveDateTime:n,issued:n,valueBoolean:r.value.valueBoolean};g.post(route("integration.store",{res_type:t.resourceType}),t).then(s=>{a.value=!0,setTimeout(()=>{a.value=!1},3e3)}).catch(s=>{console.error("Error creating user:",s),i.value=!0,setTimeout(()=>{i.value=!1},3e3)})};return(n,t)=>(l(),u("div",null,[e("form",{onSubmit:b(f,["prevent"])},[e("div",T,[j,e("div",k,[e("div",N,[d(S,{for:"text",value:"Asesmen Nyeri"}),e("div",V,[p(e("select",{id:"status","onUpdate:modelValue":t[0]||(t[0]=s=>r.value.valueBoolean=s),required:"",class:"text-sm mt-1 block w-full outline-none border-2 border-neutral-grey-0 ring-0 focus:border-original-teal-300 focus:ring-original-teal-300 rounded-xl shadow-sm px-2.5 h-fit"},q,512),[[v,r.value.valueBoolean]])]),d(w,{class:"mt-1"})])])]),e("div",C,[e("div",D,[d(x,{type:"submit",class:"teal-button text-original-white-0"},{default:h(()=>[y("Submit")]),_:1})])]),a.value?(l(),u("p",E,"Sukses!")):m("",!0),i.value?(l(),u("p",$,"Gagal!")):m("",!0)],40,B)]))}};export{P as default};