/**
 * Registers a new block to show the list of users.
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
