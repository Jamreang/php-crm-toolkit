<?php

/**
 * AlexaSDK_SoapRequestsGenerator.class.php
 * 
 * This file defines the AlexaSDK_SoapRequestsGenerator class that used to generate the body of SOAP requests messages
 * 
 * @author alexacrm.com
 * @version 1.0
 * @package AlexaSDK
 */

class AlexaSDK_SoapRequestsGenerator{
	
		/**
		 * Generate a Create Request
		 * @ignore
		 */
		public static function generateCreateRequest(AlexaSDK_Entity $entity) {
			/* Generate the CreateRequest message */
			$createRequestDOM = new DOMDocument();
			$createNode = $createRequestDOM->appendChild($createRequestDOM->createElementNS('http://schemas.microsoft.com/xrm/2011/Contracts/Services', 'Create'));
			$createNode->appendChild($createRequestDOM->importNode($entity->getEntityDOM(), true));
			/* Return the DOMNode */
			return $createNode;
		}
		
		/**
		 * Generate an Update Request
		 * @ignore
		 */
		public static function generateUpdateRequest(AlexaSDK_Entity $entity) {
			/* Generate the UpdateRequest message */
			$updateRequestDOM = new DOMDocument();
			$updateNode = $updateRequestDOM->appendChild($updateRequestDOM->createElementNS('http://schemas.microsoft.com/xrm/2011/Contracts/Services', 'Update'));
			$updateNode->appendChild($updateRequestDOM->importNode($entity->getEntityDOM(), true));
			/* Return the DOMNode */
			return $updateNode;
		}
	
		/**
		 * Generate a Delete Request
		 * @param AlexaSDK_Entity $entity the Entity to delete
		 * @ignore
		 */
		public static function generateDeleteRequest(AlexaSDK_Entity $entity) {
			/* Generate the DeleteRequest message */
			$deleteRequestDOM = new DOMDocument();
			$deleteNode = $deleteRequestDOM->appendChild($deleteRequestDOM->createElementNS('http://schemas.microsoft.com/xrm/2011/Contracts/Services', 'Delete'));
			$deleteNode->appendChild($deleteRequestDOM->createElement('entityName', $entity->logicalName));
			$deleteNode->appendChild($deleteRequestDOM->createElement('id', $entity->ID));
			/* Return the DOMNode */
			return $deleteNode;
		}
		
		public static function generateRetrieveMetadataChangesRequest() {
			/* Generate the ExecuteAction message */
			$retrieveMetadataChangesRequestDom = new DOMDocument();

			$retrieveMetadataNode = $retrieveMetadataChangesRequestDom->appendChild($retrieveMetadataChangesRequestDom->createElementNS('http://schemas.microsoft.com/xrm/2011/Contracts/Services', 'Execute'));
			$retrieveMetadataNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');

			$requestNode = $retrieveMetadataNode->appendChild($retrieveMetadataChangesRequestDom->createElement('request'));
			$requestNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:b', 'http://schemas.microsoft.com/xrm/2011/Contracts');

			$parametersNode = $requestNode->appendChild($retrieveMetadataChangesRequestDom->createElement('b:Parameters'));
			$parametersNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:c', 'http://schemas.datacontract.org/2004/07/System.Collections.Generic');

			$propertyNode = $parametersNode->appendChild($retrieveMetadataChangesRequestDom->createElement('b:KeyValuePairOfstringanyType'));
			/* Set the Property Name */
			$propertyNode->appendChild($retrieveMetadataChangesRequestDom->createElement('c:key', "Query"));
			/* Now create the XML Node for the Value */
			$valueNode = $propertyNode->appendChild($retrieveMetadataChangesRequestDom->createElement('c:value'));
			/* Set the Type of the Value */
			$valueNode->setAttribute('i:type', 'd:EntityQueryExpression');
			$valueNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:d', 'http://schemas.microsoft.com/xrm/2011/Metadata/Query');
			$valueNode->appendChild(new DOMText(""));

			/* $propertyNode = $parametersNode->appendChild($retrieveMetadataChangesRequestDom->createElement('b:KeyValuePairOfstringanyType'));
			  $propertyNode->appendChild($retrieveMetadataChangesRequestDom->createElement('c:key', "ClientVersionStamp"));
			  $valueNode = $propertyNode->appendChild($retrieveMetadataChangesRequestDom->createElement('c:value'));
			  $valueNode->setAttribute('i:type', 'd:string');
			  $valueNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:d', 'http://www.w3.org/2001/XMLSchema');
			  $valueNode->appendChild(new DOMText("395720!05/11/2015 17:04:05"));
			 */

			$requiestIdNode = $requestNode->appendChild($retrieveMetadataChangesRequestDom->createElement('b:RequestId'));
			$requiestIdNode->setAttribute('i:nil', 'true');
			$requestNode->appendChild($retrieveMetadataChangesRequestDom->createElement('b:RequestName', "RetrieveMetadataChanges"));

			return $retrieveMetadataNode;
		}
		
		/**
		 * Generate a Retrieve Request
		 * @ignore
		 */
		public static function generateRetrieveRequest($entityType, $entityId, $columnSet) {
			/* Generate the RetrieveRequest message */
			$retrieveRequestDOM = new DOMDocument();
			$retrieveNode = $retrieveRequestDOM->appendChild($retrieveRequestDOM->createElementNS('http://schemas.microsoft.com/xrm/2011/Contracts/Services', 'Retrieve'));
			$retrieveNode->appendChild($retrieveRequestDOM->createElement('entityName', $entityType));
			$retrieveNode->appendChild($retrieveRequestDOM->createElement('id', $entityId));
			$columnSetNode = $retrieveNode->appendChild($retrieveRequestDOM->createElement('columnSet'));
			$columnSetNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:b', 'http://schemas.microsoft.com/xrm/2011/Contracts');
			$columnSetNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');
			/* Add the columns requested, if specified */
			if ($columnSet != NULL && count($columnSet) > 0) {
				$columnSetNode->appendChild($retrieveRequestDOM->createElement('b:AllColumns', 'false'));
				$columnsNode = $columnSetNode->appendChild($retrieveRequestDOM->createElement('b:Columns'));
				$columnsNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:c', 'http://schemas.microsoft.com/2003/10/Serialization/Arrays');
				foreach ($columnSet as $columnName) {
					$columnsNode->appendChild($retrieveRequestDOM->createElement('c:string', strtolower($columnName)));
				}
			} else {
				/* No columns specified, request all of them */
				$columnSetNode->appendChild($retrieveRequestDOM->createElement('b:AllColumns', 'true'));
			}
			/* Return the DOMNode */
			return $retrieveNode;
		}
		
		/**
		 * Utility function to generate the XML for a Retrieve Organization request
		 * This XML can be sent as a SOAP message to the Discovery Service to determine all Organizations
		 * available on that service.
		 * @return DOMNode containing the XML for a RetrieveOrganizationRequest message
		 * @ignore
		 */
		public static function generateRetrieveOrganizationRequest() {
			$retrieveOrganizationRequestDOM = new DOMDocument();
			$executeNode = $retrieveOrganizationRequestDOM->appendChild($retrieveOrganizationRequestDOM->createElementNS('http://schemas.microsoft.com/xrm/2011/Contracts/Discovery', 'Execute'));
			$requestNode = $executeNode->appendChild($retrieveOrganizationRequestDOM->createElement('request'));
			$requestNode->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'RetrieveOrganizationsRequest');
			$requestNode->appendChild($retrieveOrganizationRequestDOM->createElement('AccessType', 'Default'));
			$requestNode->appendChild($retrieveOrganizationRequestDOM->createElement('Release', 'Current'));

			return $executeNode;
		}
		
		
		/**
		 * Generate a Retrieve Multiple Request
		 * @ignore
		 */
		public static function generateRetrieveMultipleRequest($queryXML, $pagingCookie = NULL, $limitCount = NULL, $pageNumber = NULL) {
			if ($pagingCookie != NULL) {
				/* Turn the queryXML into a DOMDocument so we can manipulate it */
				$queryDOM = new DOMDocument();
				$queryDOM->loadXML($queryXML);
				if ($pageNumber == NULL) {
					$newPage = AlexaSDK::getPageNo($pagingCookie) + 1;
					//echo 'Doing paging - Asking for page: '.$newPage.PHP_EOL;
				} else {
					$newPage = $pageNumber;
				}
				/* Modify the query that we send: Add the Page number */
				$queryDOM->documentElement->setAttribute('page', $newPage);
				/* Modify the query that we send: Add the Paging-Cookie (note - HTMLENTITIES automatically applied by DOMDocument!) */
				$queryDOM->documentElement->setAttribute('paging-cookie', $pagingCookie);
				/* Update the Query XML with the new structure */
				$queryXML = $queryDOM->saveXML($queryDOM->documentElement);
				//echo PHP_EOL.PHP_EOL.$queryXML.PHP_EOL.PHP_EOL;
			}
			/* Turn the queryXML into a DOMDocument so we can manipulate it */
			$queryDOM = new DOMDocument();
			$queryDOM->loadXML($queryXML);
			/* Find the current limit, if there is one */
			$currentLimit = AlexaSDK::getMaximumRecords() + 1;
			if ($queryDOM->documentElement->hasAttribute('count')) {
				$currentLimit = $queryDOM->documentElement->getAttribute('count');
			}
			/* Determine the preferred limit (passed by argument, or 5000 if not set) */
			$preferredLimit = ($limitCount == NULL) ? AlexaSDK::getMaximumRecords() : $limitCount;
			if ($preferredLimit > AlexaSDK::getMaximumRecords())
				$preferredLimit = AlexaSDK::getMaximumRecords();
			/* If the current limit is not set, or is greater than the preferred limit, over-ride it */
			if ($currentLimit > $preferredLimit) {
				/* Modify the query that we send: Change the Count */
				$queryDOM->documentElement->setAttribute('count', $preferredLimit);
				/* Update the Query XML with the new structure */
				$queryXML = $queryDOM->saveXML($queryDOM->documentElement);
				//echo PHP_EOL.PHP_EOL.$queryXML.PHP_EOL.PHP_EOL;
			}
			/* Generate the RetrieveMultipleRequest message */
			$retrieveMultipleRequestDOM = new DOMDocument();
			$retrieveMultipleNode = $retrieveMultipleRequestDOM->appendChild($retrieveMultipleRequestDOM->createElementNS('http://schemas.microsoft.com/xrm/2011/Contracts/Services', 'RetrieveMultiple'));
			$queryNode = $retrieveMultipleNode->appendChild($retrieveMultipleRequestDOM->createElement('query'));
			$queryNode->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'b:FetchExpression');
			$queryNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:b', 'http://schemas.microsoft.com/xrm/2011/Contracts');
			$queryNode->appendChild($retrieveMultipleRequestDOM->createElement('b:Query', htmlentities($queryXML)));
			/* Return the DOMNode */
			return $retrieveMultipleNode;
		}
	
		/**
		 * Generate a Retrieve Entity Request
		 * @ignore
		 */
		public static function generateRetrieveEntityRequest($entityType, $entityId = NULL, $entityFilters = NULL, $showUnpublished = false) {
			/* We can use either the entityType (Logical Name), or the entityId, but not both. */
			/* Use ID by preference, if not set, default to 0s */
			if ($entityId != NULL)
				$entityType = NULL;
			else
				$entityId = AlexaSDK::EmptyGUID;

			/* If no entityFilters are supplied, assume "All" */
			if ($entityFilters == NULL)
				$entityFilters = 'Entity Attributes Privileges Relationships';

			/* Generate the RetrieveEntityRequest message */
			$retrieveEntityRequestDOM = new DOMDocument();
			$executeNode = $retrieveEntityRequestDOM->appendChild($retrieveEntityRequestDOM->createElementNS('http://schemas.microsoft.com/xrm/2011/Contracts/Services', 'Execute'));
			$requestNode = $executeNode->appendChild($retrieveEntityRequestDOM->createElement('request'));
			$requestNode->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'i:type', 'b:RetrieveEntityRequest');
			$requestNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:b', 'http://schemas.microsoft.com/xrm/2011/Contracts');
			$parametersNode = $requestNode->appendChild($retrieveEntityRequestDOM->createElement('b:Parameters'));
			$parametersNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:c', 'http://schemas.datacontract.org/2004/07/System.Collections.Generic');
			/* EntityFilters */
			$keyValuePairNode1 = $parametersNode->appendChild($retrieveEntityRequestDOM->createElement('b:KeyValuePairOfstringanyType'));
			$keyValuePairNode1->appendChild($retrieveEntityRequestDOM->createElement('c:key', 'EntityFilters'));
			$valueNode1 = $keyValuePairNode1->appendChild($retrieveEntityRequestDOM->createElement('c:value', $entityFilters));
			$valueNode1->setAttribute('i:type', 'd:EntityFilters');
			$valueNode1->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:d', 'http://schemas.microsoft.com/xrm/2011/Metadata');
			/* MetadataId */
			$keyValuePairNode2 = $parametersNode->appendChild($retrieveEntityRequestDOM->createElement('b:KeyValuePairOfstringanyType'));
			$keyValuePairNode2->appendChild($retrieveEntityRequestDOM->createElement('c:key', 'MetadataId'));
			$valueNode2 = $keyValuePairNode2->appendChild($retrieveEntityRequestDOM->createElement('c:value', $entityId));
			$valueNode2->setAttribute('i:type', 'd:guid');
			$valueNode2->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:d', 'http://schemas.microsoft.com/2003/10/Serialization/');
			/* RetrieveAsIfPublished */
			$keyValuePairNode3 = $parametersNode->appendChild($retrieveEntityRequestDOM->createElement('b:KeyValuePairOfstringanyType'));
			$keyValuePairNode3->appendChild($retrieveEntityRequestDOM->createElement('c:key', 'RetrieveAsIfPublished'));
			$valueNode3 = $keyValuePairNode3->appendChild($retrieveEntityRequestDOM->createElement('c:value', ($showUnpublished ? 'true' : 'false')));
			$valueNode3->setAttribute('i:type', 'd:boolean');
			$valueNode3->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:d', 'http://www.w3.org/2001/XMLSchema');
			/* LogicalName */
			$keyValuePairNode4 = $parametersNode->appendChild($retrieveEntityRequestDOM->createElement('b:KeyValuePairOfstringanyType'));
			$keyValuePairNode4->appendChild($retrieveEntityRequestDOM->createElement('c:key', 'LogicalName'));
			$valueNode4 = $keyValuePairNode4->appendChild($retrieveEntityRequestDOM->createElement('c:value', $entityType));
			$valueNode4->setAttribute('i:type', 'd:string');
			$valueNode4->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:d', 'http://www.w3.org/2001/XMLSchema');
			/* Request ID and Name */
			$requestNode->appendChild($retrieveEntityRequestDOM->createElement('b:RequestId'))->setAttribute('i:nil', 'true');
			$requestNode->appendChild($retrieveEntityRequestDOM->createElement('b:RequestName', 'RetrieveEntity'));
			/* Return the DOMNode */
			return $executeNode;
		}
		
		/**
		 * Generate a ExecuteAction Request
		 * 
		 * @param string $requestName name of Action to request
		 * @param Array(optional)
		 * @ignore
		 */
		public static function generateExecuteActionRequest($requestName, $parameters = NULL, $requestType = NULL) {
			/* Generate the ExecuteAction message */
			$executeActionRequestDOM = new DOMDocument();

			$executeActionNode = $executeActionRequestDOM->appendChild($executeActionRequestDOM->createElementNS('http://schemas.microsoft.com/xrm/2011/Contracts/Services', 'Execute'));
			$executeActionNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:i', 'http://www.w3.org/2001/XMLSchema-instance');

			$requestNode = $executeActionNode->appendChild($executeActionRequestDOM->createElement('request'));
			$requestNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:b', 'http://schemas.microsoft.com/xrm/2011/Contracts');
			/* Set request type */
			if ($requestType) {
				$requestNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:e', 'http://schemas.microsoft.com/crm/2011/Contracts');
				$requestNode->setAttribute('i:type', 'e:' . $requestType);
			}

			$parametersNode = $requestNode->appendChild($executeActionRequestDOM->createElement('b:Parameters'));
			$parametersNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:c', 'http://schemas.datacontract.org/2004/07/System.Collections.Generic');

			if ($parameters != NULL && is_array($parameters)) {

				foreach ($parameters as $parameter) {
					/* Create a Key/Value Pair of String/Any Type */
					$propertyNode = $parametersNode->appendChild($executeActionRequestDOM->createElement('b:KeyValuePairOfstringanyType'));
					/* Set the Property Name */
					$propertyNode->appendChild($executeActionRequestDOM->createElement('c:key', $parameter["key"]));
					/* Determine the Type, Value and XML Namespace for this field */
					$xmlValue = $parameter["value"];
					$xmlValueChild = NULL;
					$xmlType = strtolower($parameter["type"]);
					$xmlTypeNS = 'http://www.w3.org/2001/XMLSchema';
					/* Special Handing for certain types of field */
					switch ($xmlType) {
						case 'entityreference':
							/* EntityReference - Get a entity xml structure */
							$xmlType = 'EntityReference';
							$xmlValue = NULL;
							$xmlTypeNS = 'http://schemas.microsoft.com/xrm/2011/Contracts';
							
							
							if ($xmlValue != NULL) {
								$valueNode->setAttribute('i:type', 'b:EntityReference');
								$valueNode->appendChild($executeActionRequestDOM->createElement('b:Id', ($this->propertyValues[$property]['Value']) ? $this->propertyValues[$property]['Value']->ID : ""));
								$valueNode->appendChild($executeActionRequestDOM->createElement('b:LogicalName', ($this->propertyValues[$property]['Value']) ? $this->propertyValues[$property]['Value']->logicalname : ""));
								$valueNode->appendChild($executeActionRequestDOM->createElement('b:Name'))->setAttribute('i:nil', 'true');
							} else {
								$valueNode->setAttribute('i:nil', 'true');
							}
							
							//$valueNode = 
							
							$xmlValueChild = $executeActionRequestDOM->createElement('b:Value', $parameter["value"]);
							
							break;
						case 'memo':
							/* Memo - This gets treated as a normal String */
							$xmlType = 'string';
							break;
						case 'integer':
							/* Integer - This gets treated as an "int" */
							$xmlType = 'int';
							break;
						case 'uniqueidentifier':
							/* Uniqueidentifier - This gets treated as a guid */
							$xmlType = 'guid';
							break;
						case 'money':
							$xmlType = 'Money';
							//$xmlTypeNS = NULL;
							$xmlValue = $executeActionRequestDOM->createElement('c:Value', $parameter["value"]);
							break;
						case 'picklist':
						case 'state':
						case 'status':
							/* OptionSetValue - Just get the numerical value, but as an XML structure */
							$xmlType = 'OptionSetValue';
							$xmlTypeNS = 'http://schemas.microsoft.com/xrm/2011/Contracts';
							$xmlValue = NULL;
							$xmlValueChild = $executeActionRequestDOM->createElement('b:Value', $parameter["value"]);
							break;
						case 'boolean':
							/* Boolean - Just get the numerical value */
							$xmlValue = ($parameter["value"]) ? "true" : "false";
							break;
						case 'guid':
							$xmlType = 'guid';
							$xmlTypeNS = 'http://schemas.microsoft.com/2003/10/Serialization/';
							break;
						case 'base64binary':
							$xmlType = 'base64Binary';
							break;
						case 'string':
						case 'int':
						case 'decimal':
						case 'double':
							/* No special handling for these types */
							break;
						default:
							/* If we're using Default, Warn user that the XML handling is not defined */
							trigger_error('No Create/Update handling implemented for type ' . $xmlType . ' used by field ' . $property, E_USER_WARNING);
					}
					/* Now create the XML Node for the Value */
					$valueNode = $propertyNode->appendChild($executeActionRequestDOM->createElement('c:value'));
					/* Set the Type of the Value */
					$valueNode->setAttribute('i:type', 'd:' . $xmlType);
					$valueNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:d', $xmlTypeNS);
					/* If there is a child node needed, append it */
					if ($xmlValueChild != NULL) {
						$valueNode->appendChild($xmlValueChild);
					}
					/* If there is a value, set it */
					if ($xmlValue != NULL) {
						$valueNode->appendChild(new DOMText($xmlValue));
					}
				}
			}
			$requiestIdNode = $requestNode->appendChild($executeActionRequestDOM->createElement('b:RequestId'));
			$requiestIdNode->setAttribute('i:nil', 'true');
			$requestNode->appendChild($executeActionRequestDOM->createElement('b:RequestName', $requestName));
			return $executeActionNode;
		}
	
}
