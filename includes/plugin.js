
// data
const d = {
  
  fields: wpApiCurrentPrayers.fields,
  prayers: wpApiCurrentPrayers.prayers,
  translations: wpApiCurrentPrayers.translations,
  
}

// elements
const el = {

  alert: $('#alert'),
  contactPrayerRequests: $('#contactPrayerRequests'),
  groupPrayerRequests: $('#groupsPrayerRequests'),

}

// methods
const m = {

  checkIfFieldsExist: () => {
    
    contactExists = d.fields.contacts.hasOwnProperty('current_prayer_requests')
    groupsExists = d.fields.groups.hasOwnProperty('current_prayer_requests')
    let notExists = []

    if(!contactExists) {

      notExists.push('contacts')
      el.contactPrayerRequests.hide()
      el.alert.show()

    }

    if(!groupsExists) {

      notExists.push('groups')
      el.groupPrayerRequests.hide()
      el.alert.show()

    }
      
    if(notExists.length > 0) {

      let text = ''
      notExists.forEach((field) => {
        text += d.translations[field] + ', '
      })

      el.alert.html(d.translations.the_system_does_not_have_current_prayers_field_for_the_followings + ' ' + text)

    }

  }, // e.o check if fields exist

  renderPrayerRequests: () => {
    
    // render for each contacts
    d.prayers.contacts.forEach((prayer) => {
      m.renderHtml(el.contactPrayerRequests, prayer)
    })

    // render for each groups
    d.prayers.groups.forEach((prayer) => {
      m.renderHtml(el.groupPrayerRequests, prayer)
    })

  }, // e.o render prayer requests

  renderHtml(el, data) {

      const requestBlock = $('<div class="request-block"></div>')
      const html = {
        title: $('<h3></h3>'),
        content: `<div>${d.translations.request}</div><p>${data.meta_value}</p>`,
      }

      html.title.html(`${d.translations.from} : ${data.post_title}`)

      if(data.group_type) {
        const span = $('<span class="group-type"></span>')
        
        html.title.append(span.html(d.translations[data.group_type]))
      }

      requestBlock.html(html.title).append(html.content)
      el.append(requestBlock)

  }, // e.o render html

}


$(document).ready(function() {

  m.checkIfFieldsExist()
  m.renderPrayerRequests()

})
