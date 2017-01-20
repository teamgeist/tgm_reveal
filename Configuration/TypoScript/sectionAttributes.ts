lib.tgm_reveal {
	background {
		# FAL-URL
		falUrl = FILES
		falUrl.references.table = pages
		falUrl.references.uid.field = uid

		falUrl.renderObj = TEXT
		falUrl.renderObj.data = file:current:publicUrl

		# DIRECT-URL
		directLink = TEXT
		directLink.insertData = 1
	}

	####### BACKGROUND-COLOR ######################################################################################################
	backgroundColor = TEXT
	backgroundColor.insertData = 1
	backgroundColor.noTrimWrap = | data-background-color="{field:tx_tgm_reveal_bg_color}"||

	####### BACKGROUND-IMAGE ######################################################################################################
	backgroundImage {
		# FAL-URL
		falUrl < lib.tgm_reveal.background.falUrl
		falUrl.references.fieldName = tx_tgm_reveal_bg_image_selectBy_fal
		falUrl.renderObj.noTrimWrap = | data-background-image="|"|

		# DIRECT-URL
		directLink < lib.tgm_reveal.background.directLink
		directLink.value = {field:tx_tgm_reveal_bg_image_selectBy_link}
		directLink.noTrimWrap = | data-background-image="|"|
		directLink.if.isTrue.field = tx_tgm_reveal_bg_image_selectBy_link

		# SIZE
		size = TEXT
		size.insertData = 1
		size.value = {field:tx_tgm_reveal_bg_image_size}
		size.noTrimWrap = | data-background-size="|"|
		size.if.isTrue.field = tx_tgm_reveal_bg_image_size
		# POSITION
		position = TEXT
		position.insertData = 1
		position.value = {field:tx_tgm_reveal_bg_image_position}
		position.noTrimWrap = | data-background-position="|"|
		position.if.isTrue.field = tx_tgm_reveal_bg_image_position
		# REPEAT
		repeat = TEXT
		repeat.insertData = 1
		repeat.value = {field:tx_tgm_reveal_bg_image_repeat}
		repeat.noTrimWrap = | data-background-repeat="|"|
		repeat.if.isTrue.field = tx_tgm_reveal_bg_image_repeat
	}

	####### BACKGROUND-VIDEO ######################################################################################################
	backgroundVideo {
		# FAL-URL
		falUrl < lib.tgm_reveal.background.falUrl
		falUrl.references.fieldName = tx_tgm_reveal_bg_video_selectBy_fal
		falUrl.renderObj.noTrimWrap = | data-background-video="|"|

		# DIRECT-URL
		directLink < lib.tgm_reveal.background.directLink
		directLink.value = {field:tx_tgm_reveal_bg_video_selectBy_link}
		directLink.noTrimWrap = | data-background-video="|"|
		directLink.if.isTrue.field = tx_tgm_reveal_bg_video_selectBy_link

		# LOOP
		loop = TEXT
		loop.insertData = 1
		loop.value = data-background-video-loop
		loop.noTrimWrap = | | |
		loop.value.if.value = true
		loop.value.if.equals.field = tx_tgm_reveal_bg_video_loop
		# MUTED
		muted = TEXT
		muted.insertData = 1
		muted.value = data-background-video-muted
		muted.noTrimWrap = | | |
		muted.value.if.value = true
		muted.value.if.equals.field = tx_tgm_reveal_bg_video_muted
	}

	####### BACKGROUND-IFRAME #####################################################################################################
	backgroundIFrame.url = TEXT
	backgroundIFrame.url.insertData = 1
	backgroundIFrame.url.noTrimWrap = | data-background-iframe="{field:tx_tgm_reveal_bg_iframe}"||

	####### SECTION-ATTRIBUTES ####################################################################################################
	attribute {
		# TRANSITION
		transition = TEXT
		transition.insertData = 1
		transition.noTrimWrap = | data-transition="{field:tx_tgm_reveal_transition}"||
		transition.if.isTrue.field = tx_tgm_reveal_transition
		# STATE
		state = TEXT
		state.insertData = 1
		state.noTrimWrap = | data-state="{field:tx_tgm_reveal_state}"||
		state.if.isTrue.field = tx_tgm_reveal_state
		# MARKDOWN
		markdown = TEXT
		markdown.noTrimWrap = | data-markdown||
		markdown.if.value = true
		markdown.if.equals.field = tx_tgm_reveal_markdown
		# TRIM
		trim = TEXT
		trim.noTrimWrap = | data-trim||
		trim.if.value = true
		trim.if.equals.field = tx_tgm_reveal_trim
		# ADDITIONAL-USER-ATTRIBUTES
		additional = TEXT
		additional.insertData = 1
		additional.noTrimWrap = | {field:tx_tgm_reveal_additional_attributes}||
		additional.if.isTrue.field = tx_tgm_reveal_additional_attributes
	}

	####### SPEAKER-NOTES #########################################################################################################
	speakerNotes = TEXT
	speakerNotes.insertData = 1
	speakerNotes.value = {field:tx_tgm_reveal_notes}
	speakerNotes.wrap = <aside class="notes">|</aside>
	speakerNotes.if.isTrue.field = tx_tgm_reveal_notes
}