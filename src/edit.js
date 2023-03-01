/**
 * Retrieves the translation of text.
 */
import { __ } from '@wordpress/i18n';

import { InspectorControls, RichText, useBlockProps } from '@wordpress/block-editor';
import {
	CheckboxControl,
	ColorPicker
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';


/**
 * Editor css.
 */
import './editor.scss';

/**
 * Edit.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { backgroundColor, color, title, idChecked, nameChecked, emailChecked } = attributes;

	return (
		<div { ...useBlockProps() } style={ { background: backgroundColor, color: color } }>
			{ __( 'Shows list of the API users', 'list-users' ) }
			<InspectorControls>
				<CheckboxControl
					label={ __( 'Hide User ID', 'list-users' ) }
					help={ __( 'Is the user ID visible?', 'list-users' ) }
					checked={ idChecked }
					onChange={ idChecked => setAttributes( { idChecked } ) }
				/>
				<CheckboxControl
					label={ __( 'Hide User name', 'list-users' ) }
					help={ __( 'Is the user name visible?', 'list-users' ) }
					checked={ nameChecked }
					onChange={ nameChecked => setAttributes( { nameChecked } ) }
				/>
				<CheckboxControl
					label={ __( 'Hide User email', 'list-users' ) }
					help={ __( 'Is the user email visible', 'list-users' ) }
					checked={ emailChecked }
					onChange={ emailChecked => setAttributes( { emailChecked } ) }
				/>
				{ __( 'Background', 'list-users' ) }
				<ColorPicker
					color={backgroundColor}
					onChange={ backgroundColor => setAttributes( { backgroundColor } )}
					enableAlpha
					defaultValue="#000"
				/>
				{ __( 'Text color', 'list-users' ) }
				<ColorPicker
					color={color}
					onChange={ color => setAttributes( { color } )}
					enableAlpha
					defaultValue="#000"
				/>
			</InspectorControls>
		
			<RichText
					className="list-users__title"
					placeholder={ __( 'Title', 'list-users' ) }
					tagName="h2"
					value={ title }
					onChange={ ( title ) => setAttributes( { title } ) }/>
			<ServerSideRender
                block="ivdimova/list-users"
                attributes={ attributes }
            />
		</div>
		
	);
}