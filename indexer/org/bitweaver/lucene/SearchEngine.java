package org.bitweaver.lucene;

import java.util.HashMap;
import java.util.Vector;


import org.apache.lucene.analysis.Analyzer;
import org.apache.lucene.analysis.StopAnalyzer;
import org.apache.lucene.document.Document;
import org.apache.lucene.index.IndexReader;
import org.apache.lucene.queryParser.QueryParser;
import org.apache.lucene.search.Hits;
import org.apache.lucene.search.IndexSearcher;
import org.apache.lucene.search.Query;

/**
 * @author Dave Palmer <dave@engineworks.org>
 */
public class SearchEngine {
	protected IndexSearcher searcher = null;
	protected Query query = null;
	protected Hits hits = null;

	public static void main(String[] args) throws Exception {
		try {
			SearchEngine searcher = new SearchEngine();
			HashMap results = searcher.search( args[0], args[1], args[2], args[3] );
			System.out.println( "Search complete\n" + results );
		} catch( ArrayIndexOutOfBoundsException e ) {
			System.out.println("Usage: javac org.bitweaver.SearchEngine </path/to/index> <matchType> <search string> <comma delimited list of search fields>");
		}
	}


	public SearchEngine() {}

	public HashMap search (String index, String matchType, String queryString, String queryFields)
		throws Exception
	{
		return this.search( index, matchType, queryString, queryFields, 1, 200);
	}

	public HashMap search (String index, String matchType, String queryString, String queryFields, int startScore, int maxRows)
		throws Exception
	{
		try {
			if (index == null || index.equals(""))
				throw new Exception ("Index cannot be null or empty!");
			if (matchType == null || matchType.equals(""))
				throw new Exception ("matchType cannot be null or empty!");
			if (queryString == null || queryString.equals(""))
				throw new Exception ("query string cannot be null or empty!");

			searcher = new IndexSearcher(IndexReader.open(index));

			Analyzer analyzer = new StopAnalyzer();

			StringBuffer qStr = new StringBuffer();
			String[] fields = queryFields.split( "," );
			for( int i = 0; i < fields.length; i++ ) {
				qStr.append( fields[i].trim()+":\"" + queryString.trim() + "\" " );
				if( i < fields.length - 1 ) {
					qStr.append(matchType+" ");
				}
			}

			query = QueryParser.parse(qStr.toString(), "title", analyzer);
			hits = searcher.search(query);

			HashMap results = new HashMap();

			int count = hits.length() < (maxRows * 1.2) ? hits.length() : maxRows;
			if (count == 0) {
				return results;
			} else {

				HashMap metaData = new HashMap();
				metaData.put("hits", new Integer(count).toString());
				metaData.put("query", queryString);

				results.put("meta_data", metaData);
				Vector rows = new Vector();
				for (int i = 0; i < count; i++) {
					Document doc = hits.doc(i);

					HashMap row = new HashMap();
					String score = "";
					score = new Float(hits.score(i)).toString();

					row.put("score", score);
					for( int j = 0; j < fields.length; j++ ) {
						String docValue = doc.get(fields[j]);
						if( docValue != null ) {
							row.put(fields[j], docValue);
						}
					}
					rows.addElement(row);
				}
				results.put("rows", rows);
//				WddxSerializer ws = new WddxSerializer();
//				java.io.StringWriter sw = new java.io.StringWriter();
//				ws.serialize(results, sw);
				return results;
			}
		}
		catch (Exception ex){
			throw new Exception ("SearchEngine.search >> exception: "+ex.toString());
		}
	}
}
