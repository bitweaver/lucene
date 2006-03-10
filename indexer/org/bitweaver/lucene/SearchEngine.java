package org.bitweaver.lucene;

import java.util.Hashtable;
import java.util.Vector;


import org.apache.lucene.analysis.Analyzer;
import org.apache.lucene.analysis.StopAnalyzer;
import org.apache.lucene.document.Document;
import org.apache.lucene.index.IndexReader;
import org.apache.lucene.queryParser.QueryParser;
import org.apache.lucene.search.Hits;
import org.apache.lucene.search.IndexSearcher;
import org.apache.lucene.search.Query;

import com.allaire.wddx.WddxSerializer;

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
			String results = searcher.search( args[0], args[1], args[2], args[3] );
			System.out.println( "Search complete\n" + results );
		} catch( ArrayIndexOutOfBoundsException e ) {
			System.out.println("Usage: javac org.bitweaver.SearchEngine </path/to/index> <matchType> <search string> <comma delimited list of search fields>");
		}
	}


	public SearchEngine() {}

	public String search (String index, String matchType, String queryString, String queryFields)
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

			int count = hits.length();
			if (count == 0) {
				return "<wddxPacket version='1.0'><header/><data><string>No matches found for: "+queryString+"</string></data></wddxPacket>";
			} else {

				Hashtable results = new Hashtable();
				Hashtable metaData = new Hashtable();
				metaData.put("hits", new Integer(count).toString());
				metaData.put("query", queryString);

				results.put("meta_data", metaData);
				Vector rows = new Vector();
				for (int i = 0; i < count; i++) {
					Document doc = hits.doc(i);

					Hashtable row = new Hashtable();
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
				WddxSerializer ws = new WddxSerializer();
				java.io.StringWriter sw = new java.io.StringWriter();
				ws.serialize(results, sw);
				return sw.toString();
			}
		}
		catch (Exception ex){
			throw new Exception ("SearchEngine.search >> exception: "+ex.toString());
		}
	}
}
