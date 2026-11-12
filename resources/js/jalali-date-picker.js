const MONTHS=['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
const WEEK=['ش','ی','د','س','چ','پ','ج'];
const nf=new Intl.NumberFormat('fa-IR',{useGrouping:false});
const latin=s=>s.replace(/[۰-۹٠-٩]/g,c=>{let i='۰۱۲۳۴۵۶۷۸۹'.indexOf(c);return i<0?String('٠١٢٣٤٥٦٧٨٩'.indexOf(c)):String(i);});
const fa=n=>String(n).replace(/\d/g,d=>nf.format(+d));
const pad=n=>String(n).padStart(2,'0');
const fmt=new Intl.DateTimeFormat('en-US-u-ca-persian-nu-latn',{year:'numeric',month:'numeric',day:'numeric',timeZone:'UTC'});
function jp(d){let p=Object.fromEntries(fmt.formatToParts(d).filter(x=>x.type!=='literal').map(x=>[x.type,+x.value]));return{y:p.year,m:p.month,d:p.day};}
function gd(y,m,d){for(let i=0;i<390;i++){let x=new Date(Date.UTC(y+621,2,1)+i*86400000),p=jp(x);if(p.y===y&&p.m===m&&p.d===d)return{y:x.getUTCFullYear(),m:x.getUTCMonth()+1,d:x.getUTCDate()};}return null;}
function parse(s){let a=latin(s.trim()).match(/^(\d{4})[\/\-.](\d{1,2})[\/\-.](\d{1,2})$/);if(!a)return null;let y=+a[1],m=+a[2],d=+a[3],g=gd(y,m,d);return m>0&&m<13&&d>0&&d<32&&g?{y,m,d,g}:null;}
function today(){let p=Object.fromEntries(new Intl.DateTimeFormat('en-CA',{year:'numeric',month:'2-digit',day:'2-digit',timeZone:'Asia/Tehran'}).formatToParts(new Date()).filter(x=>x.type!=='literal').map(x=>[x.type,+x.value]));return jp(new Date(Date.UTC(p.year,p.month-1,p.day)));}
/**
 * ساعت فارسی با خروجی استاندارد ۲۴ ساعته (HH:mm).
 */
function buildPersianTimePicker(time, root) {
    const wrapper = document.createElement('div');
    wrapper.className = 'mt-2 grid grid-cols-3 gap-2';
    wrapper.dir = 'rtl';

    function makeSelect(suffix, labelText, placeholder) {
        const field = document.createElement('div');
        const label = document.createElement('label');
        const select = document.createElement('select');

        label.htmlFor = time.id + '-' + suffix;
        label.textContent = labelText;
        label.className = 'mb-2 block text-xs font-bold text-gray-700';

        select.id = time.id + '-' + suffix;
        select.className = 'w-full rounded-xl border border-gray-300 bg-white px-2 py-3 text-sm';
        select.required = true;
        select.disabled = root.hidden;
        select.setAttribute('aria-label', labelText);

        const empty = document.createElement('option');
        empty.value = '';
        empty.textContent = placeholder;
        empty.disabled = true;
        empty.selected = true;
        select.appendChild(empty);

        field.append(label, select);
        wrapper.appendChild(field);

        return select;
    }

    const hour = makeSelect('hour', 'ساعت', 'ساعت');
    const minute = makeSelect('minute', 'دقیقه', 'دقیقه');
    const period = makeSelect('period', 'بازه زمانی', 'انتخاب کنید');

    for (let h = 1; h <= 12; h++) {
        const option = document.createElement('option');
        option.value = String(h);
        option.textContent = fa(pad(h));
        hour.appendChild(option);
    }

    for (let m = 0; m < 60; m++) {
        const option = document.createElement('option');
        option.value = pad(m);
        option.textContent = fa(pad(m));
        minute.appendChild(option);
    }

    [
        { value: 'am', label: 'قبل از ظهر' },
        { value: 'pm', label: 'بعد از ظهر' }
    ].forEach(item => {
        const option = document.createElement('option');
        option.value = item.value;
        option.textContent = item.label;
        period.appendChild(option);
    });

    function setFrom24Hour(value) {
        const match = value.match(/^(\\d{2}):(\\d{2})$/);
        if (!match) return;

        const h = Number(match[1]);
        hour.value = String(h % 12 || 12);
        minute.value = match[2];
        period.value = h < 12 ? 'am' : 'pm';
    }

    function updateValue() {
        if (!hour.value || !minute.value || !period.value) {
            time.value = '';
            return;
        }

        let h = Number(hour.value);

        if (period.value === 'pm' && h !== 12) {
            h += 12;
        } else if (period.value === 'am' && h === 12) {
            h = 0;
        }

        time.value = pad(h) + ':' + minute.value;
        time.dispatchEvent(new Event('change', { bubbles: true }));
    }

    [hour, minute, period].forEach(select => {
        select.addEventListener('change', updateValue);
    });

    if (time.value) {
        setFrom24Hour(time.value);
    }

    time.insertAdjacentElement('beforebegin', wrapper);
    time.type = 'hidden';
    time.required = false;

    // همگام‌سازی فعال/غیرفعال بودن انتخاب‌گر با نمایش فیلد برگشت.
    time.form?.addEventListener('change', () => {
        const disabled = root.hidden || time.disabled;
        [hour, minute, period].forEach(select => {
            select.disabled = disabled;
        });
    });
}

function init(root){
 let native=root.querySelector('[data-jalali-native]'),ui=root.querySelector('[data-jalali-ui]'),date=root.querySelector('[data-jalali-date]'),time=root.querySelector('[data-jalali-time]'),cal=root.querySelector('[data-jalali-calendar]'),label=root.querySelector('[data-jalali-fallback-label]');
 if(!native||!ui||!date||!time||!cal)return;let chosen=null,y=null,m=null;
 const set=p=>{chosen=p;y=p.y;m=p.m;date.value=[p.y,pad(p.m),pad(p.d)].map(fa).join('/');date.setCustomValidity('');};
 const sync=()=>{let p=parse(date.value);if(!p||!time.value)return false;native.value=p.g.y+'-'+pad(p.g.m)+'-'+pad(p.g.d)+'T'+time.value;return true;};
 const close=()=>{cal.hidden=true;date.setAttribute('aria-expanded','false');};
 function draw(){let p=chosen||today();y??=p.y;m??=p.m;let first=gd(y,m,1);if(!first)return;let days=[];for(let i=0;i<390;i++){let q=jp(new Date(Date.UTC(y+621,2,1)+i*86400000));if(q.y===y&&q.m===m)days.push(q.d);}let offset=(new Date(Date.UTC(first.y,first.m-1,first.d)).getUTCDay()+1)%7,cells=Array(offset).fill('<span></span>');days.forEach(d=>{let active=chosen&&chosen.y===y&&chosen.m===m&&chosen.d===d;cells.push('<button type="button" data-day="'+d+'" class="min-h-10 rounded-lg text-sm '+(active?'bg-green-900 text-white':'hover:bg-green-50')+'">'+fa(d)+'</button>');});cal.innerHTML='<div class="mb-3 flex items-center justify-between"><button type="button" data-shift="-1" aria-label="ماه قبل">‹</button><b>'+MONTHS[m-1]+' '+fa(y)+'</b><button type="button" data-shift="1" aria-label="ماه بعد">›</button></div><div class="grid grid-cols-7 text-center text-xs">'+WEEK.map(x=>'<span>'+x+'</span>').join('')+'</div><div class="grid grid-cols-7 gap-1 text-center">'+cells.join('')+'</div>';}
 let old=native.value.match(/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/);if(old){set(jp(new Date(Date.UTC(+old[1],+old[2]-1,+old[3]))));time.value=old[4]+':'+old[5];}
 buildPersianTimePicker(time, root);\n native.type='hidden';native.required=false;date.disabled=root.hidden;time.disabled=root.hidden;if(label)label.hidden=true;ui.hidden=false;date.setAttribute('aria-haspopup','dialog');
 const open=()=>{cal.hidden=false;date.setAttribute('aria-expanded','true');draw();};date.addEventListener('focus',open);date.addEventListener('click',open);
 date.addEventListener('change',()=>{let p=parse(date.value);if(p)set(p);else date.setCustomValidity('تاریخ شمسی معتبر وارد کنید.');});date.addEventListener('input',()=>date.setCustomValidity(''));time.addEventListener('change',sync);
 cal.addEventListener('click',e=>{let s=e.target.closest('[data-shift]');if(s){m+=+s.dataset.shift;if(m<1){m=12;y--;}if(m>12){m=1;y++;}draw();return;}let b=e.target.closest('[data-day]');if(b){set({y,m,d:+b.dataset.day});close();sync();}});
 root.closest('form')?.addEventListener('submit',e=>{if(root.hidden||native.disabled)return;if(!parse(date.value)||!time.value||!sync()){e.preventDefault();date.setCustomValidity('تاریخ شمسی معتبر و ساعت را وارد کنید.');date.reportValidity();}});
 document.addEventListener('click',e=>{if(!root.contains(e.target))close();});date.addEventListener('keydown',e=>{if(e.key==='Escape')close();});
}
document.addEventListener('DOMContentLoaded',()=>{try{if(fmt.resolvedOptions().calendar==='persian')document.querySelectorAll('[data-jalali-picker]').forEach(init);}catch(e){/* Keep native date-time fields as fallback. */}});
