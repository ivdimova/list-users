/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Style file for the front end.
 *
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';

/**
 * Register
 */
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,
} );
